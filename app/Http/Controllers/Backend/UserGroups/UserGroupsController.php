<?php

namespace App\Http\Controllers\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\UserGroups\UserGroupsRepositoryContract;

class UserGroupsController extends Controller {

    private $groups;

    public function __construct(UserGroupsRepositoryContract $groups) {

        $this->groups = $groups;
    }

    public function userGroups() {
        $view = [
            'operations' => $this->groups->userGroups(),
        ];
        return view('backend.operations.index', $view);
    }

    public function CreateUserGroups(Request $request) {
        
         $this->groups->CreateUserGroups($request);
      
      return response()->json(['operation' => "success"]);
    }

    public function setAdministrator() {
        $view = [
            'operations' => $this->groups->setAdministrator(),
        ];
        return view('backend.operations.index', $view);
    }

    public function postNewsFeed() {
        $view = [
            'operations' => $this->groups->postNewsFeed($request),
        ];
        return view('backend.operations.index', $view);
    }

}
