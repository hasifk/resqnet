<?php

namespace App\Services\Access\Traits;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Frontend\Auth\ResetPasswordRequest;
use App\Http\Requests\Frontend\Auth\SendResetLinkEmailRequest;

/**
 * Class ResetsPasswords
 * @package App\Services\Access\Traits
 */
trait ResetsPasswords
{
    use RedirectsUsers;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('frontend.auth.passwords.email');
    }

    /**
     * @param SendResetLinkEmailRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(SendResetLinkEmailRequest $request)
    {
        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
            $message->subject(trans('strings.emails.auth.password_reset_subject'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:

                return response()->json(['status' => 'Reset Password link sent to your registered Email Id']);

            case Password::INVALID_USER:

                return response()->json(['status' => 'Invalid User']);
        }
    }

    /**
     * @param null $token
     * @return $this
     */
    public function showResetForm($token = null)
    {
        if (is_null($token)) {

            return response()->json(['Token' => 'Null']);
        }
        return response()->json(['Token' => $token]);

    }

    /**
     * @param ResetPasswordRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return response()->json(['status' => 'Your Password has been reset']);

            default:
                return response()->json(['status' => 'Password reset request for '.$request->email.' failed']);

        }
    }

    /**
     * @param $user
     * @param $password
     */
    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);
        $user->save();
        auth()->login($user);
    }
}
