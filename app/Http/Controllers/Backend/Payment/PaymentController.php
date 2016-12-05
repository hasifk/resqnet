<?php

namespace App\Http\Controllers\Backend\Payment;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Payment\PaymentRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Access\User\User;

class PaymentController extends Controller {

    private $payment;

    public function __construct(PaymentRepositoryContract $payment) {

        $this->payment = $payment;
    }

    public function paymentSave(Request $request) {
        $paypal = $this->payment->paymentSave($request);
    }

    public function paymentDetails(Request $request) {
        if (!empty($result = $this->payment->paymentDetails($request))){
          if (!empty($user=User::withTrashed()->where('id',$request->user_id)->first())){
            return response()->json(['result' => $result,'user_status' => $user->status]);
          }
        }
        else
            return response()->json(['result' => 'No payment details Yet']);
        
    }

    public function payeeDetails() {
        if (!empty($result = $this->payment->payeeDetails())):
            return response()->json(['result' => $result]);
        else:
            return response()->json(['result' => 'No payee details Yet']);
        endif;
    }

}
