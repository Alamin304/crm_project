<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillsOfMaterialRequest extends FormRequest
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
            'product' => 'required|string|max:255',
            'product_variant' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_of_measure' => 'required|string|max:255',
            'routing' => 'nullable|string|max:255',
            'bom_type' => 'required|in:manufacture,kit',
            'manufacturing_readiness' => 'nullable|string|max:255',
            'consumption' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
