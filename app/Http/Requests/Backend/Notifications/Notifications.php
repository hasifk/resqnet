<?php

namespace App\Http\Requests\Backend\Notifications;

use Dingo\Api\Http\FormRequest;

class Notifications extends FormRequest
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
            'notif_cat' =>'required',
            'notification' =>'required',
        ];
    }
}
