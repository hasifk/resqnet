<?php
namespace App\Models\RescueOperation;

use Illuminate\Database\Eloquent\Model;


class ActiveRescuer extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'activerescuers';

    protected $guarded = ['id'];
}
