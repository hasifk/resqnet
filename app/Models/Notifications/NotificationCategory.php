<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;


/**
 * Class User
 * @package App\Models\Access\User
 */
class NotificationCategory extends Model {

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'notifcategories';
    protected $guarded = ['id'];

}
