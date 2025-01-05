<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobPostRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'occupation' => 'required',
            'experience' => 'required',
            'skills' => 'required',
            'resume' => 'nullable',
            'title_english' => 'required',
            'description_english' => 'required',
            'job_type' => 'required|digits_between:1,2',
            'job_seeker_name' => Rule::requiredIf($request->job_type == 2),
            'job_seeker_or_hire_email' => 'required',
            'job_seeker_or_hire_phone' => 'required',
            'job_seeker_currently_working' => Rule::requiredIf($request->job_type == 2),
//            'job_seeker_currently_working' => 'nullable',
            'hiring_company_name' => Rule::requiredIf($request->job_type == 1),
            'job_seeker_or_hire_job_type' => 'required',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'resume.mime' => 'Resume must be in valid format',
        ];
    }
}
