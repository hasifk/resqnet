<?php

namespace App\Models\Access\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Models\Access\Payment\Traits\Attribute\PaymentAttribute;

/**
 * Class User
 * @package App\Models\Access\User
 */
class Payment extends Model {

   // use PaymentAttribute;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $table = 'payments';
    protected $guarded = ['id'];

}
