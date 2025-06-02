<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLoyaltyProgramRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'rule_base' => 'required|string|max:255',
            'minimum_purchase' => 'required|numeric|min:0',
            'account_creation_point' => 'required|integer|min:0',
            'birthday_point' => 'required|integer|min:0',
            'redeem_type' => 'required|string|max:255',
            'minimum_point_to_redeem' => 'required|integer|min:0',
            'max_amount_receive' => 'required|numeric|min:0',
            'redeem_in_portal' => 'sometimes|boolean',
            'redeem_in_pos' => 'sometimes|boolean',
            'status' => 'required|in:enabled,disabled',
            'rule_name.*' => 'sometimes|string|max:255',
            'point_from.*' => 'sometimes|integer|min:0',
            'point_to.*' => 'sometimes|integer|min:0',
            'point_weight.*' => 'sometimes|numeric|min:0',
            'rule_status.*' => 'sometimes|in:enabled,disabled',
        ];
    }
}
