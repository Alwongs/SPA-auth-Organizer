<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DayUpdateRequest extends FormRequest
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
            'date' => 'required',
            'remains_pre' =>  'required',
            'odo_pre' =>  'required',
            'fuel' =>  'required',
            'odo_post' =>  'required',
            'work_in_red' =>  'required',
            'is_trip' =>  'required',
            'red_day' =>  'required',
            'comment' =>  'nullable',
            'remains_post' =>  'required',
            'car_id' =>  'required'
        ];
    }
}
