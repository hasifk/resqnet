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

    public function userGroups($request) {
        return UserGroup::where('user_id', $request->user_id)->get();
    }

    public function userGroup($request) {
        return UserGroup::find($request->group_id);
    }

    public function CreateUserGroups($request) {
        if ($request->has('id')):
            $obj = UserGroup::find($request->id);
        else:
            $obj = new UserGroup;
            $obj->user_id = $request->user_id;
        endif;
        $obj->name = $request->name;
        $obj->gp_pin = $request->gp_pin;
        $obj->save();
        $obj->attachUserGroupImage($request->gp_img);

        $obj1 = new Member;
        $obj1->user_id = $request->user_id;
        $obj1->group_id = $obj->id;
        $obj1->role = 1;
        $obj->save();
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
    
     public function viewMembers($request) {
        return Member::join('user_group','group_members.group_id','user_group.id')
                ->join('users','group_members.user_id','users.id')
                ->where('user_group.user_id',$request);
    }

}
