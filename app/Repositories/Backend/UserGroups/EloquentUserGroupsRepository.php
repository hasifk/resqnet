<?php

namespace App\Repositories\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\RescueOperation\AdminOperationRepositoryContract;
use App\Models\UserGroups\UserGroup;
use App\Models\UserGroups\Member;
use App\Models\Access\User\User;
use App\Models\Newsfeed\Newsfeed;

class EloquentUserGroupsRepository implements UserGroupsRepositoryContract {

    private $operation;

    public function __construct(AdminOperationRepositoryContract $operation) {

        $this->operation = $operation;
    }

    public function userGroups($request, $paginate) {
        return UserGroup::where('user_id', $request->user_id)->orderBy('id', 'desc')->paginate($paginate);
    }

    public function userGrouplists() {
        return UserGroup::orderBy('id', 'desc')->paginate(20);
    }

    public function userGroup($id) {
        return UserGroup::find($id);
    }

    public function findMembersUser($userid, $groupid) {
        return Member::where('user_id', $userid)->where('group_id', $groupid)->first();
    }

    public function totalMembers($id) {
        return Member::where('group_id', $id)->count();
    }

    public function userGroupdetails($id) {
        return UserGroup::join('group_members', 'user_group.id', '=', 'group_members.group_id')
                        ->join('users', 'group_members.user_id', 'users.id')
                        ->select('user_group.*', 'users.firstname', 'users.lastname', 'group_members.user_id')
                        ->where('user_group.user_id', $id)->orderBy('user_group.id', 'desc')->get();
    }

    public function joinUsers($request) {
        $groups = UserGroup::where('gp_pin', $request->gp_pin)->first();

        if (!empty($groups)) {
            if (empty($this->findMembersUser($request->user_id, $groups->id))) {
                $obj1 = new Member;
                $obj1->user_id = $request->user_id;
                $obj1->group_id = $groups->id;
                $obj1->role = 0;
                $obj1->save();
                return "Success";
            }
            return "Already Joined";
        }
        return "Not a Valid Pin";
    }

    public function CreateUserGroups($request) {
        if ($request->has('id')):
            $obj = UserGroup::find($request->id);
        else:
            $obj = new UserGroup;
            $obj->user_id = $request->user_id;
        endif;
        if ($request->has('name'))
            $obj->name = $request->name;
        if ($request->has('gp_pin'))
            $obj->gp_pin = $request->gp_pin;
        if ($request->has('name') && $request->has('gp_pin'))
            $obj->save();

        $obj->attachUserGroupImage($request->avatar);

        if ($request->has('count')) { //count is variable, used just for checking and it same as that of "count($request->membership_no)" 
            $return = $this->addMembers($request, $role = 1);
        } else if (!$request->has('img')):
            $obj1 = new Member;
            $obj1->user_id = $request->user_id;
            $obj1->group_id = $obj->id;
            $obj1->role = 1;
            $obj1->save();
            $return[] = "Success";
        endif;
        return $return;
    }

    public function joinedGroupLists($userid) {
        return Member::join('user_group', 'group_members.group_id', '=', 'user_group.id')
                        ->join('users', 'group_members.user_id', '=', 'users.id')
                        ->where('group_members.user_id', $userid)
                        ->select('user_group.*', 'group_members.role', 'users.firstname', 'users.lastname')
                        ->orderBy('user_group.name', 'asc')->paginate(20);
    }

    public function addMembers($request, $role = 0) {
        if (count($request->membership_no) > 0) {
            for ($i = 0; $i < count($request->membership_no); $i++) {
                if (!empty($request->membership_no[$i])) {
                    if (!empty($usersid = User::where('membership_no', $request->membership_no[$i])->value('id'))) {
                        if (empty($this->findMembersUser($usersid, $request->id))) { //To check already added or not
                            $obj1 = new Member;
                            $obj1->user_id = $usersid;
                            $obj1->group_id = $request->id;
                            $obj1->role = $role;
                            $obj1->save();
                            $return[] = "Success";
                        } else
                            $return[] = "already added";
                    } else
                        $return[] = "Not a valide Membership No.";
                } else
                    $return[] = "Empty membership No";
            }
        } else
            $return[] = "Empty Array";

        return $return;
    }

    public function postNewsFeed($request) {
        
        $group_ids=explode(",",$request->group_id);
        if (count($group_ids) > 0) {
            $f = 0;
            for ($i = 0; $i < count($group_ids); $i++) {
              $group_ids[$i]=ltrim($group_ids[$i],"[");
              $group_ids[$i]=rtrim($group_ids[$i],"]");
                if (!empty($group = $this->userGroup($group_ids[$i]))) {
                    if (!empty($this->findMembersUser($request->user_id, $group_ids[$i]))) {
                        $f++;
                        $return[] = "success :".$f.":".count($group_ids);
                    } else
                        $return[] = "Current user not a Member of $group->name Group";
                } else
                    $return[] = "No Groups Found";
            }
        } else
            $return[] = "Please select any Group";
        if (count($group_ids) == $f) {
            $obj = new Newsfeed;
            $obj->user_id = $request->user_id; //posted user id
            // $obj->newsfeed_type = $request->newsfeed_type;
            $obj->countryid = User::where('id', $request->user_id)->value('country_id');
            $obj->areaid = (!empty($request->areaid)) ? $request->areaid : '';
            $obj->group_id = $group_ids;
            $obj->newsfeed_type = "User Group";
            $obj->news_title = (!empty($request->news_title)) ? $request->news_title : '';
            $obj->news = $request->news;
            $obj->save();
            $obj->attachNewsfeedImage($request->img);
        }
        return $return;
    }

    public function viewMembers($id) {
        return Member::join('users', 'group_members.user_id', '=', 'users.id')
                        ->where('group_members.group_id', $id)
                        ->select('users.firstname', 'users.lastname', 'users.country_id', 'group_members.role', 'group_members.id', 'group_members.user_id')
                        //->orderBy('user_group.id', 'desc')
                        ->paginate(20);
    }

    public function deletegroups() {
        Member::truncate();
        \DB::table('user_group')->delete();
        //UserGroup::delete();
        //\DB::table('user_group')->truncate();
        //UserGroup::truncate();
        return 1;
    }

}
