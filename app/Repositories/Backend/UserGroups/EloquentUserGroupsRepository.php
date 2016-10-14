<?php

namespace App\Repositories\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\RescueOperation\AdminOperationRepositoryContract;
use App\Models\UserGroups\UserGroup;

class EloquentUserGroupsRepository implements UserGroupsRepositoryContract {

    private $operation;

    public function __construct(AdminOperationRepositoryContract $operation) {

        $this->operation = $operation;
    }

    public function userGroups($request) {
        return UserGroup::where('user_id',$request->user_id)->get();
    }
    public function userGroup($request) {
        return UserGroup::find($request->group_id);
    }
    public function CreateUserGroups($request) {
        $obj=new UserGroup;
        $obj->user_id=$request->user_id;
        $obj->name=$request->name;
        $obj->gp_pin=$request->gp_pin;
        //$obj->user_id=$request->user_id;
        //$obj->user_id=$request->user_id;
        $obj->save();
    }

    public function setAdministrator() {
        $view = [
            'operations' => $this->operation->getOperations(),
        ];
        return view('backend.operations.index', $view);
    }

    public function postNewsFeed($request) {
        $view = [
            'operations' => $this->operation->getOperations(),
        ];
        return view('backend.operations.index', $view);
    }

}
