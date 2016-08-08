<?php
namespace App\Models\Countries;

use Illuminate\Database\Eloquent\Model;


/**
 * Class User
 * @package App\Models\Access\User
 */
class City extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $table = 'cities';

    protected $guarded = ['id'];
}
