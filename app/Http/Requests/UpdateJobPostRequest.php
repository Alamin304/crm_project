<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobPostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'company_name' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'job_title' => 'required|string|max:255',
            'job_type' => 'required|in:full_time,part_time,contract,temporary,internship',
            'no_of_vacancy' => 'required|integer|min:1',
            'date_of_closing' => 'required|date|after:today',
            'gender' => 'required|in:male,female,any',
            'minimum_experience' => 'required|integer|min:0',
            'is_featured' => 'sometimes|boolean',
            'status' => 'sometimes|boolean',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string',
        ];
    }
}
