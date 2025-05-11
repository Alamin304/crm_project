<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticeBoardRequest extends FormRequest
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
            'notice_type' => 'required|string|max:255',
            'description' => 'required|string',
            'notice_date' => 'required|date',
            'notice_attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ];
    }
}
