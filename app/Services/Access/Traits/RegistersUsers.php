<?php

namespace App\Services\Access\Traits;

use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Http\Requests\Frontend\Auth\RegrescuerRequest;

/**
 * Class RegistersUsers
 * @package App\Services\Access\Traits
 */
trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm()
    {
         $view =$this->user->countries();
           $view['department']=$this->user->deptDetails();
            
        return view('frontend.auth.register',$view);
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {
        if (config('access.users.confirm_email')) {
            $user = $this->user->create($request->all());
            $token = \JWTAuth::fromUser($user);
            return response()->json(['token' => $token, 'user' => $user->toArray()]);
          /*  event(new UserRegistered($user));
            return redirect()->route('frontend.index')->withFlashSuccess(trans('exceptions.frontend.auth.confirmation.created_confirm'));*/
        } else {
            auth()->login($this->user->create($request->all()));
            event(new UserRegistered(access()->user()));
            return redirect($this->redirectPath());
        }
    }


    /***************************************************************************************************************/
    public function rescuerregister(RegrescuerRequest $request)
    {

        if (config('access.users.confirm_email')) {
            $user = $this->user->create($request->all());
            $token = \JWTAuth::fromUser($user);
            return response()->json(['token' => $token, 'user' => $user->toArray()]);

        } else {
            auth()->login($this->user->create($request->all()));
            event(new UserRegistered(access()->user()));
            return redirect($this->redirectPath());
        }
    }
}