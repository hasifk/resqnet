<?php
namespace App\Models\RescueOperation;

use Illuminate\Database\Eloquent\Model;
use App\Models\RescueOperation\Traits\Attribute\OperationAttribute;

class ActiveRescuer extends Model
{

    use OperationAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'activerescuers';

    protected $guarded = ['id'];
}
