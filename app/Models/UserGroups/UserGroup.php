<?php
namespace App\Models\UserGroups;
use Illuminate\Database\Eloquent\Model;



/**
 * Class User
 * @package App\Models\Access\User
 */
class UserGroup extends Model
{

//use NewsfeedAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'user_group';

    protected $guarded = ['id'];
}
