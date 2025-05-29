<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBuyRequestRequest extends FormRequest
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
            'property_name' => 'required|string|max:255',
            // 'customer_id' => 'required|exists:customers,id',
            'inspected_property' => 'boolean',
            'term' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // 'status' => 'required|in:submitted,sent,waiting for approval,approved,declined,complete,expired,cancelled',
            'client_note' => 'nullable|string',
            'admin_note' => 'nullable|string'
        ];
    }
}
