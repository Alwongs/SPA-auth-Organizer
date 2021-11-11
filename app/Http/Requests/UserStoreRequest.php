<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|min:2|max:20',
            'email' => 'required|max:50|email',
            'password' => 'required|min:8|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.min:2' => 'more than one!',
            'name.max:20' => 'choose shorter name. Non more than 20 letters!',
            'email.email' => 'incorrect email!',
            'email.max:50' => 'max 50 letters!',
            'password.min:8' => 'length is not less than 8',
            'password.max:50' => 'length is not more than 50'
        ];
    }
}
