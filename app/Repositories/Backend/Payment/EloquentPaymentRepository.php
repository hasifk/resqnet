<?php

namespace App\Repositories\Backend\Payment;

use App\Http\Controllers\Controller;
use App\Models\UserGroups\UserGroup;
use App\Models\Access\Payment\Payment;
use App\Models\Access\Payment\PaymentCredential;
use Carbon\Carbon;

class EloquentPaymentRepository implements PaymentRepositoryContract {

    public function paymentSave($request) {
        $check_payment = Payment::where('txn_id', $request->txn_id)->where('payment_type','PayPal')->first();
        if (!empty($check_payment)):
            $check_payment->ipn_track_id = $request->ipn_track_id;
            $check_payment->payment_status = $request->payment_status;
            $check_payment->save();
        else:
            $dt = Carbon::now();
            $obj = new Payment;
            $obj->user_id = $request->custom;
            $obj->txn_id = $request->txn_id;
            $obj->payment_type ='PayPal';
            $obj->ipn_track_id = $request->ipn_track_id;
            $obj->payment_status = $request->payment_status;
            $obj->subscription_ends_at = $dt->addYears(1);
            $obj->save();
        endif;
    }

    public function inAppSave($request) {
        $check_payment = Payment::where('txn_id', $request->txn_id)->where('payment_type','InApp-Purchase')->first();
        if (!empty($check_payment)):
            return true;
        else:
            $dt = Carbon::now();
            $obj = new Payment;
            $obj->user_id = $request->user_id;
            $obj->txn_id = $request->txn_id;
            $obj->payment_type ='InApp-Purchase';
            $obj->payment_status = $request->payment_status;
            $obj->subscription_ends_at = $dt->addYears(1);
            $obj->save();
            return true;
            endif;
    }

    public function paymentDetails($request) {
        return Payment::where('user_id', $request->user_id)->whereIn('payment_status', ['Completed', 'Created', 'Processed'])->orderBy('id', 'desc')->first();
    }

    public function paymentChecking($id) {
        return Payment::where('user_id', $id)->orderBy('id', 'desc')->first();
    }

    public function payeeDetails() {
        return PaymentCredential::first();
    }

    public function paidUserDetails() {
        return Payment::orderBy('id', 'desc')
                 ->get();
        
    }
    public function upgradeFromAdmin($id)
    {
        $dt = Carbon::now();
        $obj = new Payment;
        $obj->user_id = $id;
        $obj->txn_id = '#admin';
        $obj->payment_type ='From Admin';
        $obj->payment_status = 'Completed';
        $obj->subscription_ends_at = $dt->addYears(1);
        $obj->save();
        return true;
    }

}
