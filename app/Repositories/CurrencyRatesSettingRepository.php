<?php
namespace App\Repositories;

use App\Models\CurrencyRatesSetting;

class CurrencyRatesSettingRepository
{
    public function getSettings()
    {
        return CurrencyRatesSetting::firstOrCreate([]);
    }

    public function update(array $input)
    {
        $settings = $this->getSettings();
        $settings->update($input);
        return $settings;
    }
}
