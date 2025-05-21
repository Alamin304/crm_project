<?php

namespace App\Repositories;

use App\Models\TrainingProgram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TrainingProgramRepository
{
    public function create($input)
    {
        if (isset($input['attachment']) && $input['attachment']) {
            $file = $input['attachment'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('training_attachments', $fileName, 'public');
            $input['attachment'] = $filePath;
        }

        // Convert arrays to JSON
        if (isset($input['program_items'])) {
            $input['program_items'] = json_encode($input['program_items']);
        }
        if (isset($input['departments'])) {
            $input['departments'] = json_encode($input['departments']);
        }

        return TrainingProgram::create($input);
    }

    public function update($input, $id)
    {
        $trainingProgram = TrainingProgram::findOrFail($id);

        if (isset($input['attachment']) && $input['attachment']) {
            // Delete old file if exists
            if ($trainingProgram->attachment) {
                Storage::disk('public')->delete($trainingProgram->attachment);
            }

            $file = $input['attachment'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('training_attachments', $fileName, 'public');
            $input['attachment'] = $filePath;
        }

        // Convert arrays to JSON
        if (isset($input['program_items'])) {
            $input['program_items'] = json_encode($input['program_items']);
        }
        if (isset($input['departments'])) {
            $input['departments'] = json_encode($input['departments']);
        }

        $trainingProgram->update($input);
        return $trainingProgram;
    }

    public function delete($id)
    {
        $trainingProgram = TrainingProgram::findOrFail($id);

        if ($trainingProgram->attachment) {
            Storage::disk('public')->delete($trainingProgram->attachment);
        }

        $trainingProgram->delete();
        return true;
    }
}
