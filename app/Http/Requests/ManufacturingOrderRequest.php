<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturingOrderRequest extends FormRequest
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
            'deadline' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'plan_from' => 'required|date',
            'unit_of_measure' => 'required|string|max:50',
            'responsible' => 'required|string|max:255',
            'bom_code' => 'required|string|max:255',
            'reference_code' => 'nullable|string|max:255',
            'routing' => 'required|string|max:255',
        ];
    }
}
