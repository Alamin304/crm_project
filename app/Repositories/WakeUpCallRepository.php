<?php

namespace App\Repositories;

use App\Models\WakeUpCall;

class WakeUpCallRepository
{
    public function create(array $input): WakeUpCall
    {
        return WakeUpCall::create($input);
    }

    public function update(array $input, int $id): WakeUpCall
    {
        $wakeUpCall = WakeUpCall::findOrFail($id);
        $wakeUpCall->update($input);
        return $wakeUpCall;
    }
}
