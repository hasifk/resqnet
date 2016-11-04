<?php

namespace App\Models\Access\Payment\Traits\Attribute;

/**
 * Class RoleAttribute
 * @package App\Models\Access\Role\Traits\Attribute
 */
trait PaymentAttribute {
    
    public function setSubscriptionEndsAtAttribute() {
        
        return $this->attributes['subscription_ends_at']->format('m/d/Y');
    }
    
}
