<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'date' => Rule::unique('days')->where(function ($query) {
                return $query->where('car_id', $this->car_id);
            }),
            'remains_pre' => 'required',
            'odo_pre' => 'required',
            'fuel' => 'required',
            'odo_post' => 'required',
            'remains_post' => 'required',
            'work_in_red' => 'required',
            'is_trip' => 'required',
            'red_day' => 'required',
            'comment' => 'nullable',
            'car_id' => 'required'
        ];
    }
}
