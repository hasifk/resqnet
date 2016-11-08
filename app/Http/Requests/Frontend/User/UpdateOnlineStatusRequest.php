<?php

namespace App\Http\Requests\Frontend\User;


use Dingo\Api\Http\FormRequest;

/**
 * Class UpdateProfileRequest
 * @package App\Http\Requests\Frontend\User
 */
class UpdateOnlineStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'  => 'required',
            'online_status' => 'required',
        ];
    }
}