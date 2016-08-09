<?php

namespace App\Models\Notifications;

use Illuminate\Database\Eloquent\Model;
use App\Models\Notifications\Traits\Relationship\NotificationsRelationship;
use App\Models\Notifications\Traits\Attribute\NotificationsAttribute;


/**
 * Class User
 * @package App\Models\Access\User
 */
class Notification extends Model {

    use NotificationsRelationship,NotificationsAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'notifications';
    protected $guarded = ['id'];

}
