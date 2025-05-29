<?php

namespace App\Repositories;

use App\Models\RentalRequest;
use Illuminate\Support\Facades\DB;

class RentalRequestRepository
{
    public function create(array $input)
    {
        return DB::transaction(function () use ($input) {
            $rentalRequest = RentalRequest::create($input);
            return $rentalRequest;
        });
    }

    public function update(array $input, $id)
    {
        return DB::transaction(function () use ($input, $id) {
            $rentalRequest = RentalRequest::findOrFail($id);
            $rentalRequest->update($input);
            return $rentalRequest;
        });
    }

    public function delete($id)
    {
        return RentalRequest::findOrFail($id)->delete();
    }
}
