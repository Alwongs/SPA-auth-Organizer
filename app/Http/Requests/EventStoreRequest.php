<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            'title' => Rule::unique('events')->where(function ($query) {
                return $query->where('user_id', Auth::id());
            }),
            'date' => 'required',
            'type' => 'required|max:10',
            'user_id' => 'required',
            'description' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'Title should be unique!'
        ];
    }
}
