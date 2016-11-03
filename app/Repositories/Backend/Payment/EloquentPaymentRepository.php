<?php

namespace App\Repositories\Backend\Payment;

use App\Http\Controllers\Controller;
use App\Models\UserGroups\UserGroup;
use App\Models\Access\Payment\Payment;
use Carbon\Carbon;

class EloquentPaymentRepository implements PaymentRepositoryContract {

    public function paymentSave($request) {
        $dt = Carbon::now();
        $date = $dt->addYears(1);

        $obj = new Payment;
        $obj->user_id = $request->custom;
        $obj->txn_id = $request->txn_id;
        $obj->ipn_track_id = $request->ipn_track_id;
        $obj->payment_status = $request->payment_status . " " . $request->payment_date;
        $obj->subscription_ends_at = date('Y-m-d', strtotime($lastdate));
        // $obj->payment_date = $request->payment_date;
        $obj->save();
        //return $obj;
    }

    public function paymentDetails($request) {
        return Payment::where('user_id', $request->user_id)->orderBy('id', 'desc')->first();
    }

}
