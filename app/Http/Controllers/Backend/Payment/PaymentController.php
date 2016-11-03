<?php

namespace App\Http\Controllers\Backend\UserGroups;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Payment\PaymentRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller {

    private $payment;

    public function __construct(PaymentRepositoryContract $groups) {

        $this->payment = $payment;
    }

    public function paymentSave(Request $request) {
        $paypal = $this->payment->paymentSave($request);
    }

    public function paymentDetails() {
//        $view = [
//
//            'payment' => $this->payment->paymentDetails()
//        ];
        return response()->json(['operation' => $this->payment->paymentDetails()]);
    }

}
