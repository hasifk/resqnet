<?php

namespace App\Repositories\Backend\Notifications;

use App\Models\Notifications\Notifications;


interface NotificationRepositoryContract {

    public function shows();

    public function show($id) ;

    public function category() ;

    public function save($request) ;

    public function find($id) ;

    public function filter($request);
    
    public function NotificationDelete($request);
    
    public function notification($app_id, $message);

}
