<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplementaryRequest extends FormRequest
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
            'room_type' => 'required|string|max:255',
            'complementary' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
        ];
    }
}
