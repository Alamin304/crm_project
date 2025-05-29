<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBusinessBrokerRequest extends FormRequest
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
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'verification_status' => 'required|in:verified,regular',
            'privacy' => 'required|in:private,public',
        ];
    }
}
