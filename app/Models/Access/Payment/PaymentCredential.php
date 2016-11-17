<?php

namespace App\Models\Access\Payment;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Models\Access\User
 */
class PaymentCredential extends Model {

    

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $table = 'payment_credentials';
    protected $guarded = ['id'];

}
