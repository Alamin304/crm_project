<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
            'customer_name' => 'required|string|max:255',
            'table_no' => 'required|string|max:50',
            'number_of_people' => 'required|integer|min:1',
            'start_time' => 'required',
            'end_time' => 'required',
            'date' => 'required|date',
            'status' => 'nullable|in:pending,confirmed,canceled,completed',
        ];
    }
}
