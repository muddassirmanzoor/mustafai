<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBloodPostRequest extends FormRequest
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
            'title' => 'required|max:255',
            'city' => 'required|max:20',
            'blood_group' => 'required|max:20',
            'hospital' => 'required|max:50',
            'address' => 'required|max:255',
            'files' => 'nullable',
            'blood_for' => 'required',
        ];
    }
}
