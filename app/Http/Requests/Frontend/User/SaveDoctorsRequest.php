<?php

namespace App\Http\Requests\Frontend\User;

use Dingo\Api\Http\FormRequest;

/**
 * Class UpdateProfileRequest
 * @package App\Http\Requests\Frontend\User
 */
class SaveDoctorsRequest extends FormRequest
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
            'name'  => 'required',
            'phone'  => 'required|min:6|max:10|numeric',
            'user_id'=>'required|unique:doctors'
        ];
    }
}