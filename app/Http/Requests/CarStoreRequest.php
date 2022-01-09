<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CarStoreRequest extends FormRequest
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
            'car_number' => Rule::unique('cars')->where(function ($query) {
                return $query->where('user_id', Auth::id());
            }),
            'region' => 'required',
            'model' => 'required',
            'fuel_tank' => 'required',
            'summer_norm' => 'required',
            'winter_norm' => 'required',            
            'user_id' => 'required',
        ];
    }
}
