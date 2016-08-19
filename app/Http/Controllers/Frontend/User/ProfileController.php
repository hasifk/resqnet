<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Access\ProfileImagesUploadRequest;
use App\Http\Requests\Frontend\User\SaveDoctorsRequest;
use App\Http\Requests\Frontend\User\UpdateMedicalConditionRequest;
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

    private $user;
    public function __construct(UserRepositoryContract $user)
    {
        $this->user = $user;
    }
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
    public function update(UpdateProfileRequest $request)
    {
        $this->user->updateProfile(access()->id(), $request->all());
        return redirect()->route('frontend.user.dashboard')->withFlashSuccess(trans('strings.frontend.user.profile_updated'));
    }

    public function saveProfileImage(ProfileImagesUploadRequest $request) {

        if($this->user->profileImageUpload($request)):

            return response()->json(['status' => "Profile Image has been uploaded successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }

    public function saveDoctors(SaveDoctorsRequest $request) {

        if($this->user->saveDoctors($request->all())):

            return response()->json(['status' => "Doctors info Updated successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }


    public function editDoctors($id) {

        if($doctors=$this->user->findDoctor($id)):

            return response()->json(['doctors_info' => $doctors->toArray()]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }

    public function updateDoctors(SaveDoctorsRequest $request) {

        if($this->user->updateDoctors($request->all())):

            return response()->json(['status' => "Doctors info Updated successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }

    public function updateMedicalCondition(UpdateMedicalConditionRequest $request) {

        if($this->user->updateMedicalCondition($request->all())):

            return response()->json(['status' => "Medical Condition Updated successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }
}