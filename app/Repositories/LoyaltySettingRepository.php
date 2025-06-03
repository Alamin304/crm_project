<?php
namespace App\Repositories;

use App\Models\LoyaltySetting;

class LoyaltySettingRepository
{
    public function getSettings()
    {
        return LoyaltySetting::firstOrCreate([]);
    }

    public function update(array $input)
    {
        $settings = $this->getSettings();
        $settings->update($input);
        return $settings;
    }
}
