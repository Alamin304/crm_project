<?php

namespace App\Repositories;

use App\Models\Plan;
use Illuminate\Support\Facades\Storage;

class PlanRepository
{
    public function create(array $input)
    {
        $plan = Plan::create($input);

        if (isset($input['attachment'])) {
            $plan->attachment = $this->uploadAttachment($input['attachment']);
            $plan->save();
        }

        return $plan;
    }

    public function update(array $input, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($input);

        if (isset($input['attachment'])) {
            $this->deleteAttachment($plan->attachment);
            $plan->attachment = $this->uploadAttachment($input['attachment']);
            $plan->save();
        }

        return $plan;
    }

    public function delete($id)
    {
        $plan = Plan::findOrFail($id);
        $this->deleteAttachment($plan->attachment);
        return $plan->delete();
    }

    private function uploadAttachment($file)
    {
        return $file->store('plans/attachments', 'public');
    }

    private function deleteAttachment($path)
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
