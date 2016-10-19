<?php

namespace App\Repositories\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\RescueOperation\AdminOperationRepositoryContract;
use App\Models\UserGroups\UserGroup;
use App\Models\UserGroups\Member;

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

    public function totalMembers($id) {
        return Member::where('group_id', $id)->count();
    }

    public function userGroupdetails($id) {
        return UserGroup::join('group_members', 'user_group.id', '=', 'group_members.group_id')
                        ->join('users', 'group_members.user_id', 'users.id')->select('user_group.*', 'users.firstname', 'users.lastname', 'group_members.user_id')
                        ->where('user_group.user_id', $id)->orderBy('user_group.id', 'desc')->get();
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
        $obj->save();
        
        $obj->attachUserGroupImage($request->avatar);

        if (!$request->has('id')):
        $obj1 = new Member;
        $obj1->user_id = $request->user_id;
        $obj1->group_id = $obj->id;
        $obj1->role = 1;
        $obj1->save();
        endif;
    }

    public function setAdministrator($request) {
        if ($request->has('id')):
            $obj = Member::find($request->id);
        else:
            $obj1 = new Member;
            $obj1->user_id = $request->user_id;
            $obj1->group_id = $obj->id;
        endif;

        $obj1->role = 1;
        $obj->save();
    }

    public function addMembers($request) {
        $obj1 = new Member;
        $obj1->user_id = $request->user_id;
        $obj1->group_id = $obj->id;
        $obj1->role = 1;
        $obj->save();
    }

    public function postNewsFeed($request) {
        $view = [
            'operations' => $this->operation->getOperations(),
        ];
        return view('backend.operations.index', $view);
    }

    public function viewMembers($id) {
        return Member::join('users', 'group_members.user_id', 'users.id')
                        ->where('user_group.user_id', $id)->select('')->get();
    }

}
