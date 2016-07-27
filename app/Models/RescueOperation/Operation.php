<?php
namespace App\Models\RescueOperation;

use Illuminate\Database\Eloquent\Model;


class Operation extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'operations';

    protected $guarded = ['id'];
}
