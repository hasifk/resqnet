<?php

namespace App\Models\Access\Payment\Traits\Attribute;

use Carbon\Carbon;

/**
 * Class RoleAttribute
 * @package App\Models\Access\Role\Traits\Attribute
 */
trait PaymentAttribute {

    public function getSubscriptionEndsAtAttribute($value) {
        return Carbon::parse($value)->format('d-m-Y');
    }
}
