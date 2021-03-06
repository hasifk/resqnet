<?php

namespace App\Http\Requests\Backend\Location;

use Dingo\Api\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            'user_id' =>'required',
            'lat' =>'required',
            'long' =>'required'
        ];
    }
}
