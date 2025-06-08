<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetMaintenanceRequest extends FormRequest
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
           'asset_id' => 'required|integer|exists:assets,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'maintenance_type' => 'required|string',
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'completion_date' => 'nullable|date|after_or_equal:start_date',
            'warranty_improvement' => 'nullable|boolean',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
