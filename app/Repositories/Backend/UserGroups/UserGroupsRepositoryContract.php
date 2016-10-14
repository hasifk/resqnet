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

    public function userGroups($request);

    public function CreateUserGroups($request);

    public function setAdministrator();

    public function postNewsFeed($request);

    //public function getAmountOfNewsfeeds();

    //public function getNewsfeedAmount($request);
}
