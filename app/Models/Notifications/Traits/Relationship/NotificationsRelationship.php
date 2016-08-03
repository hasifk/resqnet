<?php

namespace App\Models\Notifications\Traits\Relationship;

use App\Models\Notifications\Notifications;
/**
 * Class UserRelationship
 * @package App\Models\Access\User\Traits\Relationship
 */
trait NotificationsRelationship
{

    public function resquerType(){
        return $this->belongsTo(Notifications::class, 'notif_cat');
    }
}