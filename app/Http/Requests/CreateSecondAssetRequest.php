<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSecondAssetRequest extends FormRequest
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
            'serial_number' => 'required|string|unique:second_assets|max:255',
            'asset_name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'status' => 'required|string|in:ready,pending,undeployable,archived,operational,non-operational,repairing',
            'supplier' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'order_number' => 'required|string|max:255',
            'purchase_cost' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'warranty' => 'required|integer|min:0',
            'requestable' => 'boolean',
            'for_sell' => 'boolean',
            'selling_price' => 'nullable|required_if:for_sell,true|numeric|min:0',
            'for_rent' => 'boolean',
            'rental_price' => 'nullable|required_if:for_rent,true|numeric|min:0',
            'minimum_renting_price' => 'nullable|required_if:for_rent,true|numeric|min:0',
            'unit' => 'nullable|required_if:for_rent,true|string|max:50',
            'description' => 'nullable|string',
        ];
    }
}
