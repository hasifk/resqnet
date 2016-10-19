<?php

namespace App\Http\Controllers\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\UserGroups\UserGroupsRepositoryContract;
use Illuminate\Http\Request;

class UserGroupsController extends Controller {

    private $groups;

    public function __construct(UserGroupsRepositoryContract $groups) {

        $this->groups = $groups;
    }

    public function userGroups(Request $request) {
        return response()->json(['details' => $this->groups->userGroups($request,10)]);
    }

    public function userGroup(Request $request) {
        return response()->json(['details' => $this->groups->userGroup($request->group_id)]);
    }

    public function CreateUserGroups(Request $request) {

        $this->groups->CreateUserGroups($request);

        return response()->json(['operation' => "success"]);
    }

    public function setAdministrator(Request $request) {
        $view = [
            'operations' => $this->groups->setAdministrator($request),
        ];
        return response()->json(['operation' => "success"]);
    }

    public function addMembers(Request $request) {
        $view = [
            'operations' => $this->groups->addMembers($request),
        ];
        return response()->json(['operation' => "success"]);
    }

    public function postNewsFeed() {
        $view = [
            'operations' => $this->groups->postNewsFeed($request),
        ];
        return response()->json(['operation' => "success"]);
    }
    
    public function viewMembers() {
        $view = [
            'operations' => $this->groups->viewMembers($request),
        ];
        return response()->json(['operation' => "success"]);
    }

}
