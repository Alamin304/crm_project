<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAwardListRequest extends FormRequest
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
            'award_name' => 'required|string|max:255',
            'award_description' => 'nullable|string',
            'gift_item' => 'nullable|string|max:255',
            'date' => 'required|date',
            'employee_name' => 'required|string|max:255',
            'award_by' => 'required|string|max:255',
        ];
    }
}
