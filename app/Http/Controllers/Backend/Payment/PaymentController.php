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

    public function inAppSave(Request $request) {
        $inApp = $this->payment->inAppSave($request);
        if($inApp):
            return response()->json(['status' => 'success']);
        else:
            return response()->json(['status' => 'failed']);
        endif;

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
        } else {
            $res['status'] = 'No payment details Yet';
            $res['user_status'] = $user_status;
            $res['email_confirmed'] = $email_confirmed;
            return response()->json(['result' => $res]);
        }
    }

    public function paidUserDetails() {
       $paidusers= $this->payment->paidUserDetails();

//        $view = [
//            'operations' => $this->payment->paidUserDetails(),
//        ];
//        return view('backend.operations.index', $view);
        
        return response()->json(['result' => $paidusers]);
    }

    public function payeeDetails() {
        if (!empty($result = $this->payment->payeeDetails())):
            return response()->json(['result' => $result]);
        else:
            return response()->json(['result' => 'No payee details Yet']);
        endif;
    }

    public function upgradeFromAdmin($id) {
        if (!empty($result = $this->payment->upgradeFromAdmin($id))):
            return redirect()->back()->withFlashSuccess('Account Upgraded Successfully');
        else:
            return redirect()->back()->withFlashSuccess('Unable to Upgrade Account');
        endif;
    }

}
