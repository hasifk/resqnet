<?php
namespace App\Models\Rescuer;

use Illuminate\Database\Eloquent\Model;


/**
 * Class User
 * @package App\Models\Access\User
 */
class RescuerType extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $table = 'rescuertypes';

    protected $guarded = ['id'];
}
