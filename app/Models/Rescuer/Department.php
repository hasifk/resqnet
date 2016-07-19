<?php
namespace App\Models\Rescuer;


use Illuminate\Database\Eloquent\Model;


/**
 * Class User
 * @package App\Models\Access\User
 */
class Department extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $table = 'departments';

    protected $guarded = ['id'];
}
