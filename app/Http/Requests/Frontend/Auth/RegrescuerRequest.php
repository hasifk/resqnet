<?php

namespace App\Http\Requests\Frontend\Auth;
use Dingo\Api\Http\FormRequest;


/**
 * Class RegisterRequest
 * @package App\Http\Requests\Frontend\Access
 */
class RegrescuerRequest extends FormRequest
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
            'firstname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'jurisdiction' => 'required|max:255',
            'rescuer_type_id' => 'required',
            'dept_id' => 'required|max:255',
        ];
    }
}
