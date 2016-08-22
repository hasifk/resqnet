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
        $user = $this->user->find($id);
        $url='';
        if ($user->avatar_filename && $user->avatar_extension && $user->avatar_path) {
            //$url = Storage::disk('public')->url($user->avatar_path.$user->avatar_filename.'.'.$user->avatar_extension);
            $url = \Image::make(storage_path().'/app/'. $user->avatar_path.$user->avatar_filename.'.'.$user->avatar_extension)->encode('data-url');
            //$url =storage_path() . '/app/' . $user->avatar_path.$user->avatar_filename.'.'.$user->avatar_extension;
           // $url = storage_path();
            //Storage::disk('s3')->url($filename)
           //$url = Storage::url('/app/' . $user->avatar_path.$user->avatar_filename.'.'.$user->avatar_extension);
           /* $path = storage_path() . '/app/' . $user->avatar_path.$user->avatar_filename.'.'.$user->avatar_extension;

            if(!File::exists($path)) abort(404);

            $file = File::get($path);
            $type = File::mimeType($path);

            $response = \Illuminate\Support\Facades\Response::make($file, 200);
            $response->header("Content-Type", $type);
            $url=$response;*/
            //$url=utf8_encode($url);
         // return $url;
        }
      return response()->json(['user' => $this->user->find($id)->toArray(),'profile_image_src'=>$url]);
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