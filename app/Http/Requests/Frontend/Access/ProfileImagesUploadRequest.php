<?php

namespace App\Http\Requests\Frontend\Access;

use Dingo\Api\Http\FormRequest;

class ProfileImagesUploadRequest extends FormRequest
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
            'avatar' => 'required|image|max:5000|mimes:jpeg,jpg,png',
        ];
    }
}
