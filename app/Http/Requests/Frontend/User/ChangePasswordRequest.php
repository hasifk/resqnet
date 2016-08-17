<?php

namespace App\Http\Requests\Frontend\User;


use Dingo\Api\Http\FormRequest;

/**
 * Class ChangePasswordRequest
 * @package App\Http\Requests\Frontend\Access
 */
class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->user()->canChangePassword();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required',
            'password'     => 'required|min:6|confirmed',
        ];
    }
}