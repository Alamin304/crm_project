<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoutingRequest extends FormRequest
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
            'routing_code' => 'required|unique:routings,routing_code,'.$this->route('routing').'|string|max:255',
            'routing_name' => 'required|string|max:255',
            'note' => 'nullable|string',
        ];
    }
}
