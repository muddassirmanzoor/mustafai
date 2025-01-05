<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeRequest extends FormRequest
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
            'lang' => 'required|string|in:english,urdu', // change the language codes to match your application's supported languages
        ];
    }

    public function messages()
    {
        return [
            'lang.required' => 'The language parameter is required.',
            'lang.string' => 'The language parameter must be a string.',
            'lang.in' => 'The selected language is invalid.',
        ];
    }
}
