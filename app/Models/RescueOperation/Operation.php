<?php
namespace App\Models\RescueOperation;

use App\Models\RescueOperation\Traits\Attribute\OperationAttribute;
use Illuminate\Database\Eloquent\Model;


class Operation extends Model
{
    use OperationAttribute;



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'operations';

    protected $guarded = ['id'];
}
