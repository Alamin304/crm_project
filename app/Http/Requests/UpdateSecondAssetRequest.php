<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSecondAssetRequest extends CreateSecondAssetRequest
{
    public function rules()
    {
        $rules = parent::rules();
        $rules['serial_number'] = 'required|string|max:255|unique:second_assets,serial_number,'.$this->route('second_asset');
        return $rules;
    }
}
