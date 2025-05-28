<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyOwnerRequest extends FormRequest
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
            'owner_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'email' => 'nullable|email',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vat_number' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ];
    }
}
