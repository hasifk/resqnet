<?php
namespace App\Http\Requests\Backend\Newsfeed;

use Dingo\Api\Http\FormRequest;

class CreateNewsfeedRequest extends FormRequest
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
            'news' =>'required'
        ];
    }
}
