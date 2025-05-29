<?php

namespace App\Repositories;

use App\Models\BusinessBroker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessBrokerRepository
{
    public function create($input)
    {
        return BusinessBroker::create($input);
    }

    public function update($input, $id)
    {
        $agent = BusinessBroker::findOrFail($id);

        // Handle file uploads
        if (isset($input['profile_image']) && $input['profile_image']) {
            if ($agent->profile_image) {
                Storage::disk('public')->delete($agent->profile_image);
            }
            $input['profile_image'] = $input['profile_image']->store('business_brokers', 'public');
        }

        if (isset($input['attachment']) && $input['attachment']) {
            if ($agent->attachment) {
                Storage::disk('public')->delete($agent->attachment);
            }
            $input['attachment'] = $input['attachment']->store('business_brokers/attachments', 'public');
        }

        $agent->update($input);
        return $agent;
    }
}
