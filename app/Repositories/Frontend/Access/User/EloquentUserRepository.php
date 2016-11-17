<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\Doctor\Doctor;
use App\Models\Access\User\User;
use App\Models\Countries\City;
use App\Models\Countries\Country;
use App\Models\Countries\State;
use App\Models\Rescuer\RescuerType;
use App\Models\Access\HealthInsurance\HealthInsurance;
use App\Models\Access\EmergencyContact\EmergencyContact;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Hash;
use App\Models\Access\User\SocialLogin;
use App\Repositories\Backend\Access\Role\RoleRepositoryContract;
use App\Models\UserGroups\UserGroup;
use Auth;
use App\Models\RescueOperation\Location;

/**
 * Class EloquentUserRepository
 * @package App\Repositories\Frontend\User
 */
class EloquentUserRepository implements UserRepositoryContract {

    /**
     * @var RoleRepositoryContract
     */
    protected $role;

    /**
     * @param RoleRepositoryContract $role
     */
    public function __construct(RoleRepositoryContract $role) {
        $this->role = $role;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id) {
        return User::findOrFail($id);
    }

    /**
     * @param $email
     * @return bool
     */
    public function findByEmail($email) {
        $user = User::where('email', $email)->first();

        if ($user instanceof User)
            return $user;

        return false;
    }

    /**
     * @param $token
     * @return mixed
     * @throws GeneralException
     */
    public function findByToken($token) {
        $user = User::where('confirmation_code', $token)->first();

        if (!$user instanceof User)
            throw new GeneralException(trans('exceptions.frontend.auth.confirmation.not_found'));

        return $user;
    }

    public function findDoctor($user_id) {
        return Doctor::where('user_id', $user_id)->select(['id', 'name', 'surname', 'phone'])->first();
    }

    /**
     * @param array $data
     * @param bool $provider
     * @return static
     */
    public function create(array $data, $provider = false) {
        $return = array();
        if (!empty($data['gp_pin'])) {
            for ($i = 0; $i < count($data['gp_pin']); $i++) {
                if (!empty($data['gp_pin'][$i])) {
                    $groups = UserGroup::where('gp_pin', $data['gp_pin'][$i])->first();
                    if (!empty($groups)) {
                        $group_ids[] = $groups->id;
                    } else {
                        $return[] = "invalid Group Pin: " . $data['gp_pin'][$i];
                    }
                }
            }
        }
        for ($i = 1; $i < 4; $i++) {
            if (!empty($data['emergency' . $i])) {
                if (empty($user = User::where('membership_no', $data['emergency' . $i])->first())) {
                    $return[] = "Invalid Membership No: " . $data['emergency' . $i];
                }
            }
        }
        if (count($return) > 0)
            return $return;
        $city=City::find($data['area_id']);
        $response = \Geo::geocode($city->name);
        return $response;
        $latlong=json_decode($response);
        if ($provider) {

            $user = User::create([
                        'firstname' => $data['firstname'],
                        'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
                        'fb_id' => (!empty($data['fb_id'])) ? $data['fb_id'] : '',
                        'dob' => (!empty($data['dob'])) ? $data['dob'] : '',
                        'country_id' => (!empty($data['country_id'])) ? $data['country_id'] : '',
                        'area_id' => (!empty($data['area_id'])) ? $data['area_id'] : '',
                        'jurisdiction' => (!empty($data['jurisdiction'])) ? $data['jurisdiction'] : '',
                        'displayname' => (!empty($data['display_name'])) ? $data['display_name'] : '',
                        'rescuer_type_id' => (!empty($data['rescuer_type_id'])) ? $data['rescuer_type_id'] : '',
                        'dept_name' => (!empty($data['dept_name'])) ? $data['dept_name'] : '',
                        'email' => $data['email'],
                        'password' => null,
                        'status' => 0,
                        'emergency_groups' => (!empty($group_ids)) ? json_encode($group_ids) : '',
                        'current_medical_conditions' => (!empty($data['current_medical_conditions'])) ? $data['current_medical_conditions'] : '',
                        'prior_medical_conditions' => (!empty($data['prior_medical_conditions'])) ? $data['prior_medical_conditions'] : '',
                        'allergies' => (!empty($data['allergies'])) ? $data['allergies'] : '',
                        'phone' => (!empty($data['phone'])) ? $data['phone'] : '',
//                        'subscription_id' => (!empty($data['subscription_id'])) ? $data['subscription_id'] : '',
//                        'subscription_info' => (!empty($data['subscription_info'])) ? $data['subscription_info'] : '',
//                        'subscription_plan' => (!empty($data['subscription_plan'])) ? $data['subscription_plan'] : '',
//                        'subscription_ends_at' => (!empty($data['subscription_ends_at'])) ? $data['subscription_ends_at'] : '',
                        'confirmation_code' => md5(uniqid(mt_rand(), true)),
                        'confirmed' => 1,
            ]);
        } else {
            $user = User::create([
                        'firstname' => $data['firstname'],
                        'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
                        'fb_id' => (!empty($data['fb_id'])) ? $data['fb_id'] : '',
                        'dob' => (!empty($data['dob'])) ? $data['dob'] : '',
                        'country_id' => (!empty($data['country_id'])) ? $data['country_id'] : '',
                        'area_id' => (!empty($data['area_id'])) ? $data['area_id'] : '',
                        'jurisdiction' => (!empty($data['jurisdiction'])) ? $data['jurisdiction'] : '',
                        'displayname' => (!empty($data['display_name'])) ? $data['display_name'] : '',
                        'rescuer_type_id' => (!empty($data['rescuer_type_id'])) ? $data['rescuer_type_id'] : '',
                        'dept_name' => (!empty($data['dept_name'])) ? $data['dept_name'] : '',
                        'email' => $data['email'],
                        'password' => bcrypt($data['password']),
                        'status' => 0,
                        'emergency_groups' => (!empty($group_ids)) ? json_encode($group_ids) : '',
                        'current_medical_conditions' => (!empty($data['current_medical_conditions'])) ? $data['current_medical_conditions'] : '',
                        'prior_medical_conditions' => (!empty($data['prior_medical_conditions'])) ? $data['prior_medical_conditions'] : '',
                        'allergies' => (!empty($data['allergies'])) ? $data['allergies'] : '',
                        'phone' => (!empty($data['phone'])) ? $data['phone'] : '',
//                        'subscription_id' => (!empty($data['subscription_id'])) ? $data['subscription_id'] : '',
//                        'subscription_info' => (!empty($data['subscription_info'])) ? $data['subscription_info'] : '',
//                        'subscription_plan' => (!empty($data['subscription_plan'])) ? $data['subscription_plan'] : '',
//                        'subscription_ends_at' => (!empty($data['subscription_ends_at'])) ? $data['subscription_ends_at'] : '',
                        'confirmation_code' => md5(uniqid(mt_rand(), true)),
                        'confirmed' => config('access.users.confirm_email') ? 0 : 1,
            ]);
        }

        /**
         * Add the default site role to the new user
         */
        if (empty($data['rescuer_type_id'])):
            $user->attachRole($this->role->getDefaultUserRole());
        else:
            $type = RescuerType::where('id', $data['rescuer_type_id'])->value('type');
            $role_id = \DB::table('roles')->where('name', $type)->value('id');
            $user->attachRoles(array($role_id));
        endif;

        $user->update([
            'membership_no' => $user->id . str_random(5)]);
        /**
         * If users have to confirm their email and this is not a social account,
         * send the confirmation email
         *
         * If this is a social account they are confirmed through the social provider by default
         */
        /** Emergency contact start */
        $obj = new EmergencyContact;
        $obj->user_id = $user->id;
        $obj->emergency1 = (!empty($data['emergency1'])) ? $data['emergency1'] : '';
        $obj->emergency2 = (!empty($data['emergency2'])) ? $data['emergency2'] : '';
        $obj->emergency3 = (!empty($data['emergency3'])) ? $data['emergency3'] : '';

        $obj->save();

        /** Emergency end */
        if (config('access.users.confirm_email') && $provider === false) {
            $this->sendConfirmationEmail($user);
        }

        /**
         * Return the user object
         */
        return $user->id;
    }

    public function updateUserStub($data) {
        $return = array();
        if (!empty($data['gp_pin'])) {
            for ($i = 0; $i < count($data['gp_pin']); $i++) {
                if (!empty($data['gp_pin'][$i])) {
                    $groups = UserGroup::where('gp_pin', $data['gp_pin'][$i])->first();
                    if (!empty($groups)) {
                        $group_ids[] = $groups->id;
                    } else {
                        $return[] = "invalid Group Pin: " . $data['gp_pin'][$i];
                    }
                }
            }
        }
        for ($i = 1; $i < 4; $i++) {
            if (!empty($data['emergency' . $i])) {
                if (empty($user = User::where('membership_no', $data['emergency' . $i])->first())) {
                    $return[] = "Invalid Membership No: " . $data['emergency' . $i];
                }
            }
        }
        if (count($return) > 0)
            return $return;


        $user = User::find($data['id']);
        if (!empty($user)):
            $user->firstname = $data['firstname'];
            $user->lastname = (!empty($data['lastname'])) ? $data['lastname'] : '';
            $user->dob = (!empty($data['dob'])) ? $data['dob'] : '';
            $user->jurisdiction = (!empty($data['jurisdiction'])) ? $data['jurisdiction'] : '';
            $user->emergency_groups = (!empty($group_ids)) ? json_encode($group_ids) : '';
            $user->phone = (!empty($data['phone'])) ? $data['phone'] : '';
            $user->current_medical_conditions = (!empty($data['current_medical_conditions'])) ? $data['current_medical_conditions'] : '';
            $user->prior_medical_conditions = (!empty($data['prior_medical_conditions'])) ? $data['prior_medical_conditions'] : '';
            $user->allergies = (!empty($data['allergies'])) ? $data['allergies'] : '';
            $user->save();

            $emergency_contact = EmergencyContact::where('user_id', $user->id)->first();
            if (empty($emergency_contact)):
                $emergency_contact = new EmergencyContact;
                $emergency_contact->user_id = $user->id;
            endif;
            $emergency_contact->emergency1 = (!empty($data['emergency1'])) ? $data['emergency1'] : '';
            $emergency_contact->emergency2 = (!empty($data['emergency2'])) ? $data['emergency2'] : '';
            $emergency_contact->emergency3 = (!empty($data['emergency3'])) ? $data['emergency3'] : '';
            $emergency_contact->save();
            return $user;
        endif;
        return;
    }

    public function fbLogin($request) {
        $user_id = User::where('email', $request->email)->where('fb_id', $request->fb_id)->value('id');
        $email_id = User::where('email', $request->email)->value('id');
        if (!empty($user_id)):
            $user = $this->find($user_id);
            $user->app_id = $request->app_id;
            $user->device_type = $request->device_type;
            $user->save();
            return $user;
        elseif (!empty($email_id)):
            $user = $this->find($email_id);
            if ($user->role_name != 'User'):
                return "access_denied";
            elseif (!empty($user->fb_id)):
                return "access_denied";
            endif;
            $user->fb_id = $request->fb_id;
            $user->app_id = $request->app_id;
            $user->device_type = $request->device_type;
            $user->save();
            return $user;
        else:
            $user = new User;
            $user->email = $request->email;
            $user->fb_id = $request->fb_id;
            $user->app_id = $request->app_id;
            $user->device_type = $request->device_type;
            $user->firstname = (!empty($request->firstname)) ? $request->firstname : '';
            $user->status = 0;
            $user->subscription_ends_at = (!empty($request->subscription_ends_at)) ? $request->subscription_ends_at : '';
            $user->confirmation_code = md5(uniqid(mt_rand(), true));
            $user->confirmed = config('access.users.confirm_email') ? 0 : 1;
            $user->save();
            $user->membership_no = $user->id . str_random(5);
            $user->save();
            $role_id = \DB::table('roles')->where('name', 'User')->value('id');
            $user->attachRoles(array($role_id));
            if (config('access.users.confirm_email')) {
                $this->sendConfirmationEmail($user);
            }
            return $user;
        endif;
    }

    public function updateFbInfo($data) {
        $user = User::find($data['user_id']);
        $user->firstname = $data['firstname'];
        $user->lastname = (!empty($data['lastname'])) ? $data['lastname'] : '';
        $user->country_id = (!empty($data['country_id'])) ? $data['country_id'] : null;
        $user->area_id = (!empty($data['area_id'])) ? $data['area_id'] : null;
        $user->dob = (!empty($data['dob'])) ? $data['dob'] : '';
        $user->phone = (!empty($data['phone'])) ? $data['phone'] : '';
        $user->save();
        return $user;
    }

    public function saveDoctors($request) {
        $doctor = new Doctor;
        $doctor->user_id = $request->user_id;
        $doctor->name = $request->name;
        $doctor->surname = $request->surname;
        $doctor->phone = $request->phone;
        $doctor->save();
        return $doctor;
    }

    public function updateDoctors($request) {
        $doctor = Doctor::find($request->id);
        $doctor->name = $request->name;
        $doctor->surname = $request->surname;
        $doctor->phone = $request->phone;
        $doctor->save();
        return $doctor;
    }

    public function updateMedicalCondition($request) {
        $user = User::find($request->id);
        $user->current_medical_conditions = $request->current_medical_conditions;
        $user->prior_medical_conditions = $request->prior_medical_conditions;
        $user->allergies = $request->allergies;
        $user->save();
        return $user;
    }

    public function profileImageUpload($request) {
        $obj = $this->find($request->user_id);
        $obj->attachProfileImage($request->avatar);
        return true;
    }

    /**
     * @param $data
     * @param $provider
     * @return EloquentUserRepository
     */
    public function findOrCreateSocial($data, $provider) {
        /**
         * Check to see if there is a user with this email first
         */
        $user = $this->findByEmail($data->email);

        /**
         * If the user does not exist create them
         * The true flag indicate that it is a social account
         * Which triggers the script to use some default values in the create method
         */
        if (!$user) {
            $user = $this->create([
                'name' => $data->name,
                'email' => $data->email ? : "{$data->id}@{$provider}.com",
                    ], true);
        }

        /**
         * See if the user has logged in with this social account before
         */
        if (!$user->hasProvider($provider)) {
            /**
             * Gather the provider data for saving and associate it with the user
             */
            $user->providers()->save(new SocialLogin([
                'provider' => $provider,
                'provider_id' => $data->id,
                'token' => $data->token,
                'avatar' => $data->avatar,
            ]));
        } else {
            /**
             * Update the users information, token and avatar can be updated.
             */
            $user->providers()->update([
                'token' => $data->token,
                'avatar' => $data->avatar,
            ]);
        }

        /**
         * Return the user object
         */
        return $user;
    }

    /**
     * @param $token
     * @return bool
     * @throws GeneralException
     */
    public function confirmAccount($token) {
        $user = $this->findByToken($token);

        if ($user->confirmed == 1) {
            throw new GeneralException(trans('exceptions.frontend.auth.confirmation.already_confirmed'));
        }

        if ($user->confirmation_code == $token) {
            $user->confirmed = 1;
            return $user->save();
        }

        throw new GeneralException(trans('exceptions.frontend.auth.confirmation.mismatch'));
    }

    /**
     * @param $user
     * @return mixed
     */
    public function sendConfirmationEmail($user) {
        //$user can be user instance or id
        if (!$user instanceof User) {
            $user = $this->find($user);
        }

        return Mail::send('frontend.auth.emails.confirm', ['token' => $user->confirmation_code, 'membership_no' => $user->membership_no], function ($message) use ($user) {
                    $message->to($user->email, $user->name)->subject(app_name() . ': ' . trans('exceptions.frontend.auth.confirmation.confirm'));
                });
    }

    /**
     * @param $user_id
     * @return mixed
     * @throws GeneralException
     */
    public function resendConfirmationEmail($user_id) {
        return $this->sendConfirmationEmail($this->find($user_id));
    }

    /**
     * @param $id
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function updateProfile($id, $input) {
        $user = $this->find($id);
        $user->name = $input['name'];

        if ($user->canChangeEmail()) {
            //Address is not current address
            if ($user->email != $input['email']) {
                //Emails have to be unique
                if ($this->findByEmail($input['email'])) {
                    throw new GeneralException(trans('exceptions.frontend.auth.email_taken'));
                }

                $user->email = $input['email'];
            }
        }

        return $user->save();
    }

    public function countries() {

        return Country::select(['id', 'name'])->get();
    }

    public function areas() {

        return City::select(['id', 'name'])->get();
    }

    public function states($id) {
        return State::where('country_id', $id)->get();
    }

    public function cities($id) {
        return City::where('state_id', $id)
                        ->select(['id', 'name'])
                        ->get();
    }

    public function rescuerTypeDetails() {
        return RescuerType::all();
    }

    public function updateOnlineStatus($request) {
        $obj = User::find($request->user_id);
        $obj->online_status = $request->online_status;
        $obj->save();
        return true;
    }

    /**
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function changePassword($input) {
        /* $user = $this->find(access()->id()); */
        $user = $this->find($input['user_id']);

        if (Hash::check($input['old_password'], $user->password)) {
            $user->password = bcrypt($input['password']);
            return $user->save();
        }

        throw new GeneralException(trans('exceptions.frontend.auth.password.change_mismatch'));
    }

    public function emergencyContacts($id) {
        return EmergencyContact::where('user_id', $id)->first();
    }

    public function findEmergencyContacts($id) {
        return EmergencyContact::find($id);
    }

    public function findHealthinsurance($id) {
        return HealthInsurance::find($id);
    }

    public function healthinsurance($id) {
        return HealthInsurance::where('user_id', $id)->first();
    }

    public function saveEmergencyContacts($request) {
        if ($request->has('id'))
            $obj = $this->findEmergencyContacts($request->id);
        else {
            $obj = new EmergencyContact;
            $obj->user_id = $request->user_id;
        }
        $obj->emergency1 = $request->emergency1;
        $obj->emergency2 = $request->emergency2;
        $obj->emergency3 = $request->emergency3;
        $obj->save();
    }

    public function saveHealthInsurance($request) {
        if ($request->has('id'))
            $obj = $this->findHealthinsurance($request->id);
        else {
            $obj = new HealthInsurance;
            $obj->user_id = $request->user_id;
        }
        $obj->service_provider = $request->service_provider;
        $obj->insurance_no = $request->insurance_no;
        $obj->save();
    }

    public function userDetails($id) {
        $user = $this->find($id);
        if (!empty($user->emergency_groups)) {
            $group_ids = json_decode($user->emergency_groups);
            foreach ($group_ids as $gpid) {
                $pin[] = UserGroup::where('id', $gpid)->value('gp_pin');
            }
            $user['emergency_gp_pin'] = $pin;
        }
        return $user;
    }

}
