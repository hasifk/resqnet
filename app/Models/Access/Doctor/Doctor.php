<?php

namespace App\Models\Access\Doctor;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models\Access\User
 */
class Doctor extends Model {

    //use RescureTypeRelationship,DepartmentAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'doctors';
    protected $guarded = ['id'];

}
