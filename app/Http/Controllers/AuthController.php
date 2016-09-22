<?php

namespace App\Http\Controllers;



use App\Repositories\Frontend\Access\User\UserRepositoryContract;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Crypt;

class AuthController extends Controller
{
    private $user;

    public function __construct(UserRepositoryContract $user) {

        $this->user = $user;
    }
    public function postRegister(Requests\Backend\Access\User\AuthRegisterRequest $request)
    {
        $user = [
            'firstname' => $request->get('firstname'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('passowrd'))
        ];
        $user =User::create($user);
        $token = \JWTAuth::fromUser($user);
        return response()->json(['token' => $token, 'user' => $user->toArray()]);
    }

    public function postLogin(Request $request){
        if ( \Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')]) ) {
            $user = \Auth::user();
            $user->app_id=$request->app_id;
            $user->device_type=$request->device_type;
            $user->online_status=1;
            $user->save();
            /*$token = \JWTAuth::fromUser($user);*/
            $token=Crypt::encrypt($user->id);
            return response()->json(['token' => $token,'user_id'=>$user->id,
                'user_role'=>$user->role_name,'subscription_ends_at'=>$user->subscription_ends_at]);
        } else {
            return response()->json(['status' => 'Login Failed.invalid password or username']);
        }
    }

    public function fbLogin(Requests\Frontend\Auth\FBloginRequest $request)
    {
        $user=$this->user->fbLogin($request);
        if(!empty($user)):
            if($user=='access_denied'):
                return response()->json(['status' => 'You do not have access to do that']);
             endif;
            $token=Crypt::encrypt($user->id);
            return response()->json(['token' => $token,'user_id'=>$user->id,
                'user_role'=>$user->role_name,'subscription_ends_at'=>$user->subscription_ends_at,'country_id'=>$user->country_id]);
        else:
            return response()->json(['status' => 'Failed']);
        endif;


    }

    /***************************************************************************************************************/

    public function refreshToken (Request $request) {
        return $request->user()->toArray();
    }

    public function getMe(Request $request) {
        return $request->user()->toArray();
    }
}
