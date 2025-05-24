<?php

namespace App\Repositories;

use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;

class CampaignRepository
{
    public function create(array $input)
    {
        $campaign = Campaign::create($input);

        if (isset($input['attachment'])) {
            $campaign->attachment = $this->uploadAttachment($input['attachment']);
            $campaign->save();
        }

        return $campaign;
    }

    public function update(array $input, $id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update($input);

        if (isset($input['attachment'])) {
            $this->deleteAttachment($campaign->attachment);
            $campaign->attachment = $this->uploadAttachment($input['attachment']);
            $campaign->save();
        }

        return $campaign;
    }

    public function delete($id)
    {
        $campaign = Campaign::findOrFail($id);
        $this->deleteAttachment($campaign->attachment);
        return $campaign->delete();
    }

    private function uploadAttachment($file)
    {
        return $file->store('campaigns/attachments', 'public');
    }

    private function deleteAttachment($path)
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
