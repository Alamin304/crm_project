<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeePerformanceRequest extends FormRequest
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
        //    'employee_id' => 'required|exists:employees,id',
            'review_period' => 'required|string',
            'supervisor_info' => 'required|string',
            'section_a' => 'nullable|array',
            'section_b' => 'nullable|array',
            'total_score' => 'required|numeric|min:0|max:100',
            'reviewer_name' => 'nullable|string',
            'reviewer_signature' => 'nullable|string',
            'review_date' => 'nullable|date',
            'next_review_period' => 'nullable|string',
            'employee_comments' => 'nullable|string',
            'development' => 'nullable|array',
            'goals' => 'nullable|array',
        ];
    }
}
