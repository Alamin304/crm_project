<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrgChartRequest extends FormRequest
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
            'unit_manager' => 'required|string|max:255',
            // 'parent_unit' => 'nullable|exists:org_charts,id',
            'email' => 'nullable|email',
            'user_name' => 'nullable|string',
            'host' => 'nullable|string',
            'password' => 'nullable|string',
            'encryption' => 'in:TLS,SSL,no encryption',
        ];
    }
}
