<?php

namespace App\Models\Access\HealthInsurance;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models\Access\User
 */
class HealthInsurance extends Model {

    //use RescureTypeRelationship,DepartmentAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'healthinsurance';
    protected $guarded = ['id'];

}
