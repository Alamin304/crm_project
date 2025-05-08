<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingSourceRequest extends FormRequest
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
            'booking_type' => 'required|string|max:255',
            'booking_source' => 'nullable|string|max:255',
            'commission_rate' => 'nullable|numeric|min:0',
        ];
    }
}
