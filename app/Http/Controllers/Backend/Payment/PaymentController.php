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
        $user_status = '';
        $email_confirmed = '';
        if (!empty($user = User::where('id', $request->user_id)->first())) {
            $user_status = $user->status;
            $email_confirmed = $user->confirmed;
        }
        if (!empty($result = $this->payment->paymentDetails($request))) {
            $result['user_status'] = $user_status;
            $result['email_confirmed'] = $email_confirmed;
            return response()->json(['result' => $result]);
        } else
            return response()->json(['result' => 'No payment details Yet','user_status'=>$user_status,'email_confirmed'=>$email_confirmed]);
    }

    public function payeeDetails() {
        if (!empty($result = $this->payment->payeeDetails())):
            return response()->json(['result' => $result]);
        else:
            return response()->json(['result' => 'No payee details Yet']);
        endif;
    }

}
