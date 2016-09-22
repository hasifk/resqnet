<?php

namespace App\Http\Requests\Frontend\Auth;
use Dingo\Api\Http\FormRequest;


/**
 * Class RegisterRequest
 * @package App\Http\Requests\Frontend\Access
 */
class UpdateFbRequest extends FormRequest
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
            'user_id'      =>'required',
            'firstname' => 'required|max:255',
            'country_id' => 'required|numeric',
            'area_id' => 'required|numeric',
        ];
    }
}
