<?php

namespace App\Repositories\Backend\Payment;

/**
 * Interface PaymentRepositoryContract
 * @package App\Repositories\Backend\Payment
 */
interface PaymentRepositoryContract {

    /**
     * @param $id
     * @return mixed
     */

    public function paymentSave($request);
    public function paymentChecking($id);
    public function paymentDetails($request);

}
