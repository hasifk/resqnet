<?php

namespace App\Services\Access\Traits;

use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Http\Requests\Frontend\Auth\RegrescuerRequest;
use App\Http\Requests\Frontend\Auth\UpdateRequest;
use App\Http\Requests\Frontend\Auth\UpdaterescuerRequest;

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
         $view['countries'] =$this->user->countries();
           $view['department']=$this->user->deptDetails();
      return response()->json(['countries_and_departments' => $view]);
       /* return view('frontend.auth.register',$view);*/
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
    /***************************************************************************************************************/
    public function editProfile($id)
    {
        //$user = $this->user->find($id);
        return response()->json(['user' => $this->user->find($id)->toArray()]);
    }
    /***************************************************************************************************************/
    public function updateProfile(UpdateRequest $request)
    {
        $user = $this->user->updateUserStub($request->all());
            return response()->json(['user' => $user->toArray()]);

    }
    /***************************************************************************************************************/
    public function updaterescuerProfile(UpdaterescuerRequest $request)
    {
        return response()->json(['user' => $this->user->updateUserStub      ($request->all())->toArray()]);
    }
}