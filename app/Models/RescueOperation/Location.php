<?php
namespace App\Models\RescueOperation;

use Illuminate\Database\Eloquent\Model;


class Location extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'locations';

    protected $guarded = ['id'];
}
