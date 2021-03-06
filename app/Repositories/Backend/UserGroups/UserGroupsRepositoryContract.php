<?php

namespace App\Repositories\Backend\UserGroups;

/**
 * Interface UserRepositoryContract
 * @package App\Repositories\Frontend\User
 */
interface UserGroupsRepositoryContract {

    /**
     * @param $id
     * @return mixed
     */
    //public function find($id);

    public function userGroups($request, $paginate);

    public function userGrouplists();

    public function userGroup($request);

    public function findMembersUser($userid, $groupid);
            
    public function totalMembers($id);
    
    public function userGroupdetails($id,$users);

    public function CreateUserGroups($request);
    
    public function joinUsers($request);

    public function joinedGroupLists($request);

    public function addMembers($request,$role);

    public function postNewsFeed($request);

    public function viewMembers($request);
    
    public function addEmergencyGroups($request);
    
    public function deletegroups();

    //public function getAmountOfNewsfeeds();
    //public function getNewsfeedAmount($request);
}
