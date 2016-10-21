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
        $lists = $this->groups->deletegroups();
        if (count($lists) > 0):
            foreach ($lists as $key => $value) {
                $lists[$key]['amount'] = $this->groups->totalMembers($value->id);
                if ($lists[$key]['gp_image_filename'] && $lists[$key]['gp_image_extension'] && $lists[$key]['gp_image_path']) {

                    $lists[$key]['gp_image_src'] = url('/gp_image/' . $lists[$key]['id'] . '/' . $lists[$key]['gp_image_filename'] . '300x168.' . $lists[$key]['gp_image_extension']);
                }
            }
        endif;
        $view = [
            'usergroups' => $lists
        ];
        return view('backend.UserGroups.index', $view);
    }

    public function userGroup($id) {
        $lists = $this->groups->userGroup($id);
        
       if (count($lists) > 0):
           $lists['members'] = $this->groups->viewMembers($id);
                if ($lists['gp_image_filename'] && $lists['gp_image_extension'] && $lists['gp_image_path']) {

                    $lists['gp_image_src'] = url('/gp_image/' . $lists['id'] . '/' . $lists['gp_image_filename'] . '300x168.' . $lists['gp_image_extension']);
                
            }
        endif;
        $view = [
            'usergroup' => $lists,
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
