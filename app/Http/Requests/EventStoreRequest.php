<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
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
            'title' => 'required|max:255|unique:events,title',
            'date' => 'required',
            'type' => 'required|max:10',
            'user_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'Title should be unique!'
        ];
    }
}
