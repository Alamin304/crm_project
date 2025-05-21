<?php

namespace App\Queries;

use App\Models\TrainingProgram;
use Illuminate\Database\Eloquent\Builder;

class TrainingProgramDataTable
{
    public function get(): Builder
    {
        return TrainingProgram::query()->select('training_programs.*');
    }
}
