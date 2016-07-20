<?php

namespace App\Models\Rescuer;

use Illuminate\Database\Eloquent\Model;
use App\Models\Rescuer\Traits\Relationship\RescureTypeRelationship;

/**
 * Class User
 * @package App\Models\Access\User
 */
class Department extends Model {

    use RescureTypeRelationship;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'departments';
    protected $guarded = ['id'];

}
