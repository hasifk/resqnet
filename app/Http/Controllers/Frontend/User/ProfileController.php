<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Access\ProfileImagesUploadRequest;
use App\Http\Requests\Frontend\User\EditDoctorsRequest;
use App\Http\Requests\Frontend\User\SaveDoctorsRequest;
use App\Http\Requests\Frontend\User\UpdateDoctorsRequest;
use App\Http\Requests\Frontend\User\UpdateMedicalConditionRequest;
use App\Http\Requests\Frontend\User\UpdateOnlineStatusRequest;
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
    /***************************************************************************************************************/
    public function edit()
    {
        return view('frontend.user.profile.edit')
            ->withUser(access()->user());
    }

    /***************************************************************************************************************/
    public function update(UpdateProfileRequest $request)
    {
        $this->user->updateProfile(access()->id(), $request->all());
        return redirect()->route('frontend.user.dashboard')->withFlashSuccess(trans('strings.frontend.user.profile_updated'));
    }
    /***************************************************************************************************************/
    public function saveProfileImage(ProfileImagesUploadRequest $request) {
        if (access()->hasRolesApp(['Police', 'Fire', 'Paramedic'],$request->user_id)):
        if($this->user->profileImageUpload($request)):

            return response()->json(['status' => "Profile Image has been uploaded successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;
        else:
            return response()->json(['status' => "You do not have access to do that"]);
        endif;

    }

    /***************************************************************************************************************/
    public function getAvatar($id,$image)
    {
        try
        {
            $img = \Image::make(storage_path() . '/app/public/profile/avatar/'.$id.'/' . $image)->response();
        }
        catch(\Exception $e)
        {
            return response()->json(['status' => "Image not found"]);
        }
        return $img;

    }
    /***************************************************************************************************************/

    public function saveDoctors(SaveDoctorsRequest $request) {

        if($this->user->saveDoctors($request)):

            return response()->json(['status' => "Doctors info Updated successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }

    /***************************************************************************************************************/
    public function editDoctors(EditDoctorsRequest $request) {

        if($doctors=$this->user->findDoctor($request->id)):

            return response()->json(['doctors_info' => $doctors->toArray()]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }
    /***************************************************************************************************************/
    public function updateDoctors(UpdateDoctorsRequest $request) {

        if($this->user->updateDoctors($request)):

            return response()->json(['status' => "Doctors info Updated successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }
    /***************************************************************************************************************/
    public function updateMedicalCondition(UpdateMedicalConditionRequest $request) {

        if($this->user->updateMedicalCondition($request)):

            return response()->json(['status' => "Medical Condition Updated successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }
    /***************************************************************************************************************/
    public function updateOnlineStatus(UpdateOnlineStatusRequest $request) {

        if($this->user->updateOnlineStatus($request)):

            return response()->json(['status' => "Online Status Updated successfully"]);
        else:
            return response()->json(['status' => "Failed"]);
        endif;


    }
}