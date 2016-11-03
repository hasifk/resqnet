<?php

namespace App\Models\Access\Payment;

use Illuminate\Database\Eloquent\Model;
/**
 * Class User
 * @package App\Models\Access\User
 */
class Payment extends Model {



    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    
    protected $table = 'payments';
    protected $guarded = ['id'];

}
