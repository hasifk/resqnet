<?php

namespace App\Http\Requests\Frontend\Auth;
use Dingo\Api\Http\FormRequest;


/**
 * Class RegisterRequest
 * @package App\Http\Requests\Frontend\Access
 */
class UpdaterescuerRequest extends FormRequest
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
            'jurisdiction' => 'required|max:255',
        ];
    }
}
