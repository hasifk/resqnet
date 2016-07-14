<?php

namespace App\Models;

use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;

/**
 * Class User
 * @package App\Models\Access\User
 */
class Department extends Model
{



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $table = 'department';

    protected $guarded = ['id'];
}
