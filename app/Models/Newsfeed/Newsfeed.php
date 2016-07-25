<?php
namespace App\Models\Newsfeed;

use App\Models\Access\User\Traits\UserAccess;
use App\Models\Newsfeed\Traits\Attribute\NewsfeedAttribute;
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
class Newsfeed extends Model
{

use NewsfeedAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
   // use NewsfeedAttribute;
    
    protected $table = 'newsfeeds';

    protected $guarded = ['id'];
}
