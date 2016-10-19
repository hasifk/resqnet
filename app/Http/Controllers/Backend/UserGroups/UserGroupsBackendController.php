<?php

namespace App\Http\Controllers\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\UserGroups\UserGroupsRepositoryContract;
use Illuminate\Http\Request;

class UserGroupsBackendController extends Controller {

    private $groups;

    public function __construct(UserGroupsRepositoryContract $groups) {

        $this->groups = $groups;
    }

    public function userGroups() {
        $lists = $this->groups->userGrouplists();
        foreach ($lists as $key => $value) {
            $lists[$key]['amount'] = $this->groups->totalMembers($value->id);
        }
        $view = [
            'usergroups' => $lists
        ];
        return view('backend.UserGroups.index', $view);
    }

    public function userGroup($id) {
        $lists = $this->groups->userGroup($id);
        foreach ($lists as $key => $value) {
            $lists[$key]['members'] = $this->groups->viewMembers($value->id);
        }
        $view = [
            'usergroups' => $this->groups->userGroup($id),
        ];
        return view('backend.UserGroups.show', $view);
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
