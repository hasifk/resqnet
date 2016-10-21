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
        return response()->json(['details' => $this->groups->userGroupsCreateUserGroups($request, 10)]);
    }

    public function userGroup(Request $request) {
        $lists = $this->groups->userGroup($request->group_id);
        $member = $this->groups->findMembersUser($request->user_id, $lists->id);
        $lists['role'] = $member->role;
        if ($lists->gp_image_filename && $lists->gp_image_extension && $lists->gp_image_path) {
            $lists['gp_image_src'] = url('/gp_image/' . $lists->id . '/' . $lists->gp_image_filename . '300x168.' . $lists->gp_image_extension);
        }
        return response()->json(['details' => $lists]);
    }

    public function CreateUserGroups(Request $request) {

        

        return response()->json(['operation' => $this->groups->CreateUserGroups($request)]);
    }

    public function joinUsers(Request $request) {
        return response()->json(['operation' => $this->groups->joinUsers($request)]);
    }

    public function joinedGroupLists(Request $request) {
         if(count($lists=$this->groups->joinedGroupLists($request))>0)
         {
              return response()->json(['operation' => $lists]);
         }
        return response()->json(['operation' => "No Groups"]);
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

    public function getImage($id, $image) {
        try {
            $img = \Image::make(storage_path() . '/app/public/UserGroup/image/' . $id . '/' . $image)->orientate()->response();
        } catch (\Exception $e) {
            return response()->json(['status' => "Image not found"]);
        }
        return $img;
    }

}
