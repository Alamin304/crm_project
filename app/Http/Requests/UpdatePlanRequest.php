<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'plan_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'recruited_quantity' => 'required|integer|min:1',
            'working_form' => 'required|string|max:255',
            'workplace' => 'required|string|max:255',
            'starting_salary_from' => 'required|numeric|min:0',
            'starting_salary_to' => 'required|numeric|min:0|gte:starting_salary_from',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string|max:500',
            'job_description' => 'required|string',
            'approver' => 'required|string|max:255',
            'age_from' => 'nullable|integer|min:18',
            'age_to' => 'nullable|integer|min:18|gte:age_from',
            'gender' => 'nullable|string|in:male,female,other',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'literacy' => 'required|string|max:255',
            'seniority' => 'required|string|max:255',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ];
    }
}
