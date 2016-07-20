<?php

namespace App\Models\Rescuer;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rescuer\Traits\Relationship\RescureTypeRelationship;
use App\Models\Rescuer\Traits\Attribute\DepartmentAttribute;


/**
 * Class User
 * @package App\Models\Access\User
 */
class Department extends Model {

    use RescureTypeRelationship,DepartmentAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'departments';
    protected $guarded = ['id'];

}
