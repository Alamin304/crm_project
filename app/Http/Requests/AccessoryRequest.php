<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccessoryRequest extends FormRequest
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
           'accessory_name' => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'model_number' => 'nullable|string|max:255',
            'order_number' => 'nullable|string|max:255',
            'purchase_cost' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'quantity' => 'required|integer|min:1',
            'min_quantity' => 'required|integer|min:0',
            'for_sell' => 'nullable|boolean',
            'selling_price' => 'nullable|required_if:for_sell,1|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string',
        ];
    }
}
