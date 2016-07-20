<?php

namespace App\Models\Rescuer\Traits\Relationship;

use App\Models\Rescuer\RescuerType;
/**
 * Class UserRelationship
 * @package App\Models\Access\User\Traits\Relationship
 */
trait RescureTypeRelationship
{

    public function resquerType(){
        return $this->belongsTo(RescuerType::class, 'rescuertype_id');
    }
}