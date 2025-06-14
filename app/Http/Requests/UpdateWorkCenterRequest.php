<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkCenterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:work_centers,code',
            'working_hours' => 'required|string|max:50',
            'time_efficiency' => 'required|numeric|min:0|max:100',
            'cost_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'oee_target' => 'required|numeric|min:0|max:100',
            'time_before_prod' => 'required|integer|min:0',
            'time_after_prod' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ];
    }
}
