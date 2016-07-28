<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Access\ProfileImagesUploadRequest;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Access\User\UserRepositoryContract;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Frontend
 */
class ProfileController extends Controller
{
    /**
     * @return mixed
     */
    public function edit()
    {
        return view('frontend.user.profile.edit')
            ->withUser(access()->user());
    }

    /**
     * @param  UserRepositoryContract         $user
     * @param  UpdateProfileRequest $request
     * @return mixed
     */
    public function update(UserRepositoryContract $user, UpdateProfileRequest $request)
    {
        $user->updateProfile(access()->id(), $request->all());
        return redirect()->route('frontend.user.dashboard')->withFlashSuccess(trans('strings.frontend.user.profile_updated'));
    }

    public function saveProfileImage(UserRepositoryContract $user,ProfileImagesUploadRequest $request) {

        if($user->profileImageUpload($request)):

            return response()->json(['status' => "Profile Image has been uploaded successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }
}