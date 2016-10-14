<?php

namespace App\Http\Controllers\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\UserGroups\UserGroupsRepositoryContract;

class UserGroupsController extends Controller {

    private $operation;

    public function __construct(UserGroupsRepositoryContract $operation) {

        $this->operation = $operation;
    }

    public function userGroups() {
        $view = [
            'operations' => $this->operation->userGroups($request),
        ];
        return view('backend.operations.index', $view);
    }

    public function CreateUserGroups() {
        $view = [
            'operations' => $this->operation->CreateUserGroups(),
        ];
        return view('backend.operations.index', $view);
    }

    public function setAdministrator() {
        $view = [
            'operations' => $this->operation->setAdministrator(),
        ];
        return view('backend.operations.index', $view);
    }

    public function postNewsFeed() {
        $view = [
            'operations' => $this->operation->postNewsFeed($request),
        ];
        return view('backend.operations.index', $view);
    }

}
