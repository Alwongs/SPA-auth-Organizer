<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DayStoreRequest extends FormRequest
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
            'date' => 'required|unique:days,date',
            'remains_pre' => 'required',
            'odo_pre' => 'required',
            'fuel' => 'required',
            'odo_post' => 'required',
            'remains_post' => 'required',
            'comment' => 'nullable',
            'user_id' => 'required'
        ];
    }
}
