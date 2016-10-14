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
        return response()->json(['details' => $this->groups->userGroups($request)]);
    }

    public function userGroup(Request $request) {
        return response()->json(['details' => $this->groups->userGroup($request)]);
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
