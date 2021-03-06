<?php

namespace App\Http\Controllers;

use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use App\Models\Access\Payment\Payment;

class AuthController extends Controller {

    private $user;

    public function __construct(UserRepositoryContract $user) {

        $this->user = $user;
    }

    public function postRegister(Requests\Backend\Access\User\AuthRegisterRequest $request) {
        $user = [
            'firstname' => $request->get('firstname'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ];
        $user = User::create($user);
        $token = \JWTAuth::fromUser($user);
        return response()->json(['token' => $token, 'user' => $user->toArray()]);
    }

    public function postLogin(Request $request) {
          $user_check=User::where('email', $request->email)->first();
        if(isset($user_check)):
         if($user_check->confirmed==0 && $user_check->status==0) {
             return response()->json(['email_confirmed' => '0','user_status' => 0]);
            }
             elseif($user_check->status==0) {
                 return response()->json(['user_status' => 0]);
             }
             endif;
        if (\Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {


            $user = \Auth::user();
            $user->app_id = $request->app_id;
            $user->device_type = $request->device_type;
            $user->online_status = 1;
            $user->save();

            User::where('app_id', $request->app_id)->where('id', '!=', $user->id)->update(['app_id' => '']);

            /* $token = \JWTAuth::fromUser($user); */
            $token = Crypt::encrypt($user->id);
            $subscription = "";
            if (!empty($payment = Payment::where('user_id', $user->id)->orderBy('id', 'desc')->first()))
                $subscription = $payment->subscription_ends_at;
            return response()->json(['token' => $token, 'user_id' => $user->id,
                        'user_role' => $user->role_name, 'subscription_ends_at' => $subscription,
                        'membership_no' => $user->membership_no]);
        } else {
            return response()->json(['status' => 'Login Failed.invalid password or username']);
        }
    }

    public function fbLogin(Requests\Frontend\Auth\FBloginRequest $request) {
        $user_check=User::where('email', $request->email)->first();

        if($user_check->status==0) {
            return response()->json(['user_status' => 0]);
        }
        $user = $this->user->fbLogin($request);
        if (!empty($user)):
            if ($user == 'access_denied'):
                return response()->json(['status' => 'You do not have access to do that']);
            endif;
            $token = Crypt::encrypt($user->id);
            
            $subscription = "";
            if (!empty($payment = Payment::where('user_id', $user->id)->orderBy('id', 'desc')->first()))
                $subscription = $payment->subscription_ends_at;
            
            return response()->json(['token' => $token, 'user_id' => $user->id,
                        'user_role' => $user->role_name, 'subscription_ends_at' => $subscription,
                        'fb_id' => $user->fb_id, 'country_id' => $user->country_id, 'membership_no' => $user->membership_no]);
        else:
            return response()->json(['status' => 'Failed']);
        endif;
    }

    /*     * ************************************************************************************************************ */

    public function refreshToken(Request $request) {
        return $request->user()->toArray();
    }

    public function getMe(Request $request) {
        return $request->user()->toArray();
    }

}
