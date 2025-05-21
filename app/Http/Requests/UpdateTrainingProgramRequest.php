<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingProgramRequest extends FormRequest
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
            'program_name' => 'required|string|max:255',
            'training_type' => 'required|string|max:255',
            'program_items' => 'required|array',
            'program_items.*' => 'string',
            'point' => 'required|integer|min:0',
            'departments' => 'required|array',
            'departments.*' => 'string',
            'apply_position' => 'required|string|max:255',
            'description' => 'nullable|string',
            'staff_name' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'finish_date' => 'nullable|date|after_or_equal:start_date',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240',
            'training_mode' => 'nullable|string|in:online,offline,hybrid',
            'max_participants' => 'nullable|integer|min:1',
        ];
    }
}
