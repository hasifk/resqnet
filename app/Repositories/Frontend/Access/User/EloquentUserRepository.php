<?php

namespace App\Repositories\Frontend\Access\User;

use App\Models\Access\User\User;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Hash;
use App\Models\Access\User\SocialLogin;
use App\Repositories\Backend\Access\Role\RoleRepositoryContract;

/**
 * Class EloquentUserRepository
 * @package App\Repositories\Frontend\User
 */
class EloquentUserRepository implements UserRepositoryContract
{

    /**
     * @var RoleRepositoryContract
     */
    protected $role;

    /**
     * @param RoleRepositoryContract $role
     */
    public function __construct(RoleRepositoryContract $role)
    {
        $this->role = $role;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
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

        if (! $user instanceof User)
            throw new GeneralException(trans('exceptions.frontend.auth.confirmation.not_found'));

        return $user;
    }

    /**
     * @param array $data
     * @param bool $provider
     * @return static
     */
    public function create(array $data, $provider = false)
    {
        if ($provider) {
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
                'dob' => (!empty($data['dob'])) ? $data['dob'] : '',
                'country_id' => (!empty($data['country_id'])) ? $data['country_id'] : '',
                'area_id' => (!empty($data['area_id'])) ? $data['area_id'] : '',
                'jurisdiction' => (!empty($data['jurisdiction'])) ? $data['jurisdiction'] : '',
                'displayname' => (!empty($data['display_name'])) ? $data['display_name'] : '',
                'dept_id' => (!empty($data['dept_id'])) ? $data['dept_id'] : '',
                'email' => $data['email'],
                'password' =>null,
                'status' =>0,
                'current_medical_conditions' => (!empty($data['current_medical_conditions'])) ? $data['current_medical_conditions'] : '',
                'prior_medical_conditions' => (!empty($data['prior_medical_conditions'])) ? $data['prior_medical_conditions'] : '',
                'allergies' => (!empty($data['allegries'])) ? $data['allegries'] : '',
                'phone' => (!empty($data['phone'])) ? $data['phone'] : '',
                'subscription_id' =>(!empty($data['subscription_id'])) ? $data['subscription_id'] : '',
                'subscription_info' => (!empty($data['subscription_info'])) ? $data['subscription_info'] : '',
                'subscription_plan' => (!empty($data['subscription_plan'])) ? $data['subscription_plan'] : '',
                'subscription_ends_at' => (!empty($data['subscription_ends_at'])) ? $data['subscription_ends_at'] : '',
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => 1,
            ]);
        } else {
            $user = User::create([
                'firstname' => $data['firstname'],
                'lastname' => (!empty($data['lastname'])) ? $data['lastname'] : '',
                'dob' => (!empty($data['dob'])) ? $data['dob'] : '',
                'country_id' =>(!empty($data['country_id'])) ? $data['country_id'] : '',
                'area_id' => (!empty($data['area_id'])) ? $data['area_id'] : '',
                'jurisdiction' => (!empty($data['jurisdiction'])) ? $data['jurisdiction'] : '',
                'displayname' =>(!empty($data['display_name'])) ? $data['display_name'] : '',
                'dept_id' => (!empty($data['dept_id'])) ? $data['dept_id'] : '',
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'status' =>0,
                'current_medical_conditions' => (!empty($data['current_medical_conditions'])) ? $data['current_medical_conditions'] : '',
                'prior_medical_conditions' => (!empty($data['prior_medical_conditions'])) ? $data['prior_medical_conditions'] : '',
                'allergies' => (!empty($data['allegries'])) ? $data['allegries'] : '',
                'phone' => (!empty($data['phone'])) ? $data['phone'] : '',
                'subscription_id' =>(!empty($data['subscription_id'])) ? $data['subscription_id'] : '',
                'subscription_info' => (!empty($data['subscription_info'])) ? $data['subscription_info'] : '',
                'subscription_plan' => (!empty($data['subscription_plan'])) ? $data['subscription_plan'] : '',
                'subscription_ends_at' => (!empty($data['subscription_ends_at'])) ? $data['subscription_ends_at'] : '',
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => config('access.users.confirm_email') ? 0 : 1,
            ]);
        }

        /**
         * Add the default site role to the new user
         */
        if(empty($data['rescuer_type'])):
        $user->attachRole($this->role->getDefaultUserRole());
            else:
                $role_id = \DB::table('roles')->where('name', $data['rescuer_type'])->value('id');
                $user->attachRoles(array($role_id));
                endif;

        $user->update([
            'membership_no' => $user->id.str_random(5)]);
        /**
         * If users have to confirm their email and this is not a social account,
         * send the confirmation email
         *
         * If this is a social account they are confirmed through the social provider by default
         */
        if (config('access.users.confirm_email') && $provider === false) {
            $this->sendConfirmationEmail($user);
        }

        /**
         * Return the user object
         */
        return $user;
    }

    /**
     * @param $data
     * @param $provider
     * @return EloquentUserRepository
     */
    public function findOrCreateSocial($data, $provider)
    {
        /**
         * Check to see if there is a user with this email first
         */
        $user = $this->findByEmail($data->email);

        /**
         * If the user does not exist create them
         * The true flag indicate that it is a social account
         * Which triggers the script to use some default values in the create method
         */
        if (! $user) {
            $user = $this->create([
                'name'  => $data->name,
                'email' => $data->email ? : "{$data->id}@{$provider}.com",
            ], true);
        }

        /**
         * See if the user has logged in with this social account before
         */
        if (! $user->hasProvider($provider)) {
            /**
             * Gather the provider data for saving and associate it with the user
             */
            $user->providers()->save(new SocialLogin([
                'provider'    => $provider,
                'provider_id' => $data->id,
                'token'       => $data->token,
                'avatar'      => $data->avatar,
            ]));
        }else{
             /**
             * Update the users information, token and avatar can be updated.
             */
            $user->providers()->update([
                'token'       => $data->token,
                'avatar'      => $data->avatar,
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
    public function confirmAccount($token)
    {
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
    public function sendConfirmationEmail($user)
    {
        //$user can be user instance or id
        if (! $user instanceof User) {
            $user = $this->find($user);
        }

        return Mail::send('frontend.auth.emails.confirm', ['token' => $user->confirmation_code,'membership_no' => $user->membership_no], function ($message) use ($user) {
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
    public function updateProfile($id, $input)
    {
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

    public function countriesState($param) {
        
        $countries = \DB::table('countries')->select(['id', 'name'])->get();

        if ( $request->old('country_id') ) {

            $states = \DB::table('states')
                ->where('country_id', $request->old('country_id'))
                ->select(['id', 'name'])
                ->get();

        } else {

            $states = \DB::table('states')
                ->where('country_id', 222)
                ->select(['id', 'name'])
                ->get();

        }
        if ( $request->old('state_id') ) {
            
            $cities = \DB::table('cities')
                ->where('state_id', $request->old('state_id'))
                ->select(['id', 'name'])
                ->get();
        }
        else {

            $cities = \DB::table('cities')
                ->where('state_id', 222)
                ->select(['id', 'name'])
                ->get();

        }
        $view = [
            'countries' => $countries,
            'states'    => $states,
            'cities'    => $cities
        ];
        
    }
    public function deptDetails()
    {
        
    }

    /**
     * @param $input
     * @return mixed
     * @throws GeneralException
     */
    public function changePassword($input)
    {
        $user = $this->find(access()->id());

        if (Hash::check($input['old_password'], $user->password)) {
            $user->password = bcrypt($input['password']);
            return $user->save();
        }

        throw new GeneralException(trans('exceptions.frontend.auth.password.change_mismatch'));
    }
}