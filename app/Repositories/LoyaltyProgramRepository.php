<?php

namespace App\Repositories;

use App\Models\LoyaltyProgram;
use Illuminate\Support\Facades\Auth;

class LoyaltyProgramRepository
{
     public function create(array $input)
    {
        return LoyaltyProgram::create($input);
    }

    public function update(array $input, $id)
    {
        $loyaltyProgram = LoyaltyProgram::findOrFail($id);
        $loyaltyProgram->update($input);
        return $loyaltyProgram;
    }
}
