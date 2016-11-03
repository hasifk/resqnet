<?php

namespace App\Repositories\Backend\Payment;

use App\Http\Controllers\Controller;
use App\Models\UserGroups\UserGroup;
use App\Models\Access\Payment\Payment;
use Carbon\Carbon;

class EloquentPaymentRepository implements PaymentRepositoryContract {

    public function paymentSave($request) {
        $dt = Carbon::now();
        $obj = new Payment;
        $obj->user_id = $request->custom;
        $obj->txn_id = $request->txn_id;
        $obj->ipn_track_id = $request->ipn_track_id;
        $obj->payment_status = $request->payment_status." ".$request->payment_date;
        $obj->subscription_ends_at = $dt->addYears(1);
       // $obj->payment_date = $request->payment_date;
        $obj->save();
        //return $obj;
    }

    public function paymentDetails() {
        return Payment::get();
    }

}
