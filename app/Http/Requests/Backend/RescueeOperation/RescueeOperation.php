<?php

namespace App\Http\Requests\Backend\RescueeOperation;

use Dingo\Api\Http\FormRequest;

class RescueeOperation extends FormRequest
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
            'userid' =>'required',
            'type' =>'required',
            'emergency_type'=>'required',
        ];
    }
}
