<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MembershipCardTemplateRequest extends FormRequest
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
           'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'show_subject_card' => 'boolean',
            'show_company_name' => 'boolean',
            'show_client_name' => 'boolean',
            'show_member_since' => 'boolean',
            'show_memberships' => 'boolean',
            'show_custom_field' => 'boolean',
            'text_color' => 'required|string|max:7'
        ];
        
    }
}
