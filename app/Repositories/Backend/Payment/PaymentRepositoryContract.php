<?php

namespace App\Repositories\Backend\Payment;

/**
 * Interface PayPalRepositoryContract
 * @package App\Repositories\Backend\PayPal
 */
interface PaymentRepositoryContract {

    /**
     * @param $id
     * @return mixed
     */

    public function paymentSave($request);
    public function paymentDetails();

}
