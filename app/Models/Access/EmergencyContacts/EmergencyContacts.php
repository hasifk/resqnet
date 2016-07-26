<?php

namespace App\Models\Access\EmergencyContacts;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models\Access\User
 */
class EmergencyContacts extends Model {

    //use RescureTypeRelationship,DepartmentAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'emergencycontacts';
    protected $guarded = ['id'];

}
