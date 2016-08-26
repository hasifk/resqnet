<?php

namespace App\Services\Access\Traits;

use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Http\Requests\Frontend\Auth\RegrescuerRequest;
use App\Http\Requests\Frontend\Auth\UpdateRequest;
use App\Http\Requests\Frontend\Auth\UpdaterescuerRequest;
use Illuminate\Support\Facades\File;
use Storage;

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
           $view['rescuerTypes']=$this->user->rescuerTypeDetails();
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
            if($this->user->create($request->all())):
                return response()->json(['success_info' => '00']);
            else:
                return response()->json(['success_info' => '01']);
                endif;
          /*  $token = \JWTAuth::fromUser($user);
            return response()->json(['token' => $token, 'user' => $user->toArray()]);*/
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
            if($this->user->create($request->all())):
                return response()->json(['success_info' => '00']);
            else:
                return response()->json(['success_info' => '01']);
            endif;
           /* $user = $this->user->create($request->all());
            $token = \JWTAuth::fromUser($user);
            return response()->json(['token' => $token, 'user' => $user->toArray()]);*/

        } else {
            auth()->login($this->user->create($request->all()));
            event(new UserRegistered(access()->user()));
            return redirect($this->redirectPath());
        }
    }
    /***************************************************************************************************************/
    public function editProfile($id)
    {
        $user1 = $this->user->find($id);
        $user= $user1->toArray();
        if ($user1->avatar_filename && $user1->avatar_extension && $user1->avatar_path) {

            $user['profile_image_src']=url('api/avatar/'.$user1->id.'/'.$user1->avatar_filename.'.'.$user1->avatar_extension);
        }
      return response()->json(['user' => $user]);
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

    /***************************************************************************************************************/
}