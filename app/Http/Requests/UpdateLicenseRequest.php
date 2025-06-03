<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLicenseRequest extends FormRequest
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
            'software_name' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'product_key' => 'required|string|max:255',
            'seats' => 'required|integer|min:1',
            'manufacturer' => 'required|string|max:255',
            'licensed_name' => 'required|string|max:255',
            'licensed_email' => 'required|email|max:255',
            'reassignable' => 'boolean',
            'supplier' => 'required|string|max:255',
            'order_number' => 'required|string|max:255',
            'purchase_order_number' => 'required|string|max:255',
            'purchase_cost' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'expiration_date' => 'nullable|date',
            'termination_date' => 'nullable|date',
            'depreciation' => 'required|string|max:255',
            'maintained' => 'boolean',
            'for_sell' => 'boolean',
            'selling_price' => 'nullable|required_if:for_sell,true|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
