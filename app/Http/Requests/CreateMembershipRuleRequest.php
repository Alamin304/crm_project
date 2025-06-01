<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMembershipRuleRequest extends FormRequest
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
            'customer_group' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'card' => 'required|string|max:255',
            'point_from' => 'required|integer|min:0',
            'point_to' => 'required|integer|gt:point_from',
            'description' => 'nullable|string',
        ];
    }
}
