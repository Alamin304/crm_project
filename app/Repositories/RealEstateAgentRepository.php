<?php

namespace App\Repositories;

use App\Models\RealEstateAgent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RealEstateAgentRepository
{
    public function create($input)
    {
        return RealEstateAgent::create($input);
    }

    public function update($input, $id)
    {
        $agent = RealEstateAgent::findOrFail($id);

        // Handle file uploads
        if (isset($input['profile_image']) && $input['profile_image']) {
            if ($agent->profile_image) {
                Storage::disk('public')->delete($agent->profile_image);
            }
            $input['profile_image'] = $input['profile_image']->store('real_estate_agents', 'public');
        }

        if (isset($input['attachment']) && $input['attachment']) {
            if ($agent->attachment) {
                Storage::disk('public')->delete($agent->attachment);
            }
            $input['attachment'] = $input['attachment']->store('real_estate_agents/attachments', 'public');
        }

        $agent->update($input);
        return $agent;
    }
}
