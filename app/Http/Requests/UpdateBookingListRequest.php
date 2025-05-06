<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingListRequest extends FormRequest
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
            'booking_number' => 'required|string|max:255',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after_or_equal:check_in',
            'arrival_from' => 'nullable|string|max:255',
            'booking_type' => 'nullable|string|max:255',
            'booking_reference' => 'nullable|string|max:255',
            'booking_reference_no' => 'nullable|string|max:255',
            'visit_purpose' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'room_type' => 'nullable|string|max:255',
            'room_no' => 'nullable|string|max:255',
            'adults' => 'nullable|integer|min:0',
            'children' => 'nullable|integer|min:0',
            'booking_status' => 'boolean',
        ];
    }
}
