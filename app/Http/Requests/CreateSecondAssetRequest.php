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
            'serial_number' => 'required|string|max:255|unique:assets',
            'asset_name' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'supplier' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'warranty_months' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'selling_price' => 'nullable|required_if:for_sale,true|numeric|min:0',
            'rental_price' => 'nullable|required_if:for_rent,true|numeric|min:0',
            'minimum_renting_days' => 'nullable|required_if:for_rent,true|numeric|min:1',
            'rental_unit' => 'nullable|required_if:for_rent,true|string',
        ];
    }

    public function messages()
    {
        return [
            'selling_price.required_if' => 'Selling price is required when asset is marked for sale',
            'rental_price.required_if' => 'Rental price is required when asset is marked for rent',
            'minimum_renting_days.required_if' => 'Minimum renting days is required when asset is marked for rent',
            'rental_unit.required_if' => 'Rental unit is required when asset is marked for rent',
        ];
    }

}
