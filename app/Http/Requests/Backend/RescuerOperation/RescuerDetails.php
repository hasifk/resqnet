<?php

namespace App\Http\Requests\Backend\RescuerOperation;

use Dingo\Api\Http\FormRequest;

class RescuerDetails extends FormRequest
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
            'active_rescuers_id' =>'required',
        ];
    }
}
