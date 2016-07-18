<?php
namespace App\Models\Resquer;

use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;
//use App\Models\Newsfeed\Traits\Attribute\NewsfeedAttribute;

/**
 * Class User
 * @package App\Models\Access\User
 */
class Resquer extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'resquertypes';

    protected $guarded = ['id'];
}
