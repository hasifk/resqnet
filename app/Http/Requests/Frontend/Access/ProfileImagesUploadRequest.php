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
        /*$rules = [];

        $image = $this->file('avatar');

        $mimeTypes = [
            config('mimes.jpeg'),
            config('mimes.jpg'),
            config('mimes.jpe'),
            config('mimes.png')
        ];

        $mimeTypes = array_flatten($mimeTypes);

        if ( ! in_array($image->getClientMimeType(), $mimeTypes) ) {
            $rules['avatar'] = 'required';
        } else {
            $rules['avatar'] = 'image|max:5000|mimes:jpeg,jpg,png';
        }

        return $rules;*/
        return [
            'avatar' => 'required|image|max:5000|mimes:jpeg,jpg,png',
        ];
    }
}
