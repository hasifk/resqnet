<?php

namespace App\Services\Access\Traits;

use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Requests\Frontend\Auth\RegisterRequest;
use App\Http\Requests\Frontend\Auth\RegrescuerRequest;
use App\Http\Requests\Frontend\Auth\UpdateFbRequest;
use App\Http\Requests\Frontend\Auth\UpdateRequest;
use App\Http\Requests\Frontend\Auth\UpdaterescuerRequest;
use App\Http\Requests\Frontend\User\EditProfileRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
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
        $user='';
        return Mail::send('frontend.auth.emails.confirm', ['token' => 'sffsdf', 'membership_no' => 'sfdfsdd'], function ($message1) use ($user) {
            $message1->to('ajayvayalilnext@gmail.com', 'ajay')->subject(app_name() . ': ' . trans('exceptions.frontend.auth.confirmation.confirm'));
        });

       if (config('access.users.confirm_email')) {
            $result=$this->user->create($request->all());
            if(is_numeric($result)):
                return response()->json(['success_info' => '00','user_id' => $result]);
            else:
                return response()->json(['success_info' => '01','user_id' => $result]);
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
    public function editProfile(EditProfileRequest $request)
    {
        $user1 = $this->user->userDetails($request->user_id);
        $user= $user1->toArray();
        if ($user1->avatar_filename && $user1->avatar_extension && $user1->avatar_path) {

            $user['profile_image_src']=url('/avatar/'.$user1->id.'/'.$user1->avatar_filename.'.'.$user1->avatar_extension);
        }
       if($emrg = $this->user->emergencyContacts($request->user_id)):
           $user=array_merge($user,$emrg->toArray());
           endif;
      return response()->json(['user' => $user]);
    }
    /***************************************************************************************************************/
    public function updateProfile(UpdateRequest $request)
    {
       if ($user = $this->user->updateUserStub($request->all())):
            return response()->json(['user' => $user]);
           else:
               return response()->json(['status' => 'failed']);
               endif;

    }
    /***************************************************************************************************************/
    public function updateFbInfo(UpdateFbRequest $request)
    {
        if($user = $this->user->updateFbInfo($request->all())):
        return response()->json(['user' => $user->toArray()]);
            else:
                return response()->json(['status' => 'failed']);
        endif;

    }
    /***************************************************************************************************************/
    public function updaterescuerProfile(UpdaterescuerRequest $request)
    {
        return response()->json(['user' => $this->user->updateUserStub      ($request->all())->toArray()]);
    }

    /***************************************************************************************************************/
}