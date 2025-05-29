<?php

namespace App\Repositories;

use App\Models\BuyRequest;
use Illuminate\Support\Facades\DB;

class BuyRequestRepository
{
    public function create($input)
    {
        return BuyRequest::create($input);
    }

    public function update($input, $id)
    {
        $buyRequest = BuyRequest::findOrFail($id);
        $buyRequest->update($input);
        return $buyRequest;
    }

    public function delete($id)
    {
        $buyRequest = BuyRequest::findOrFail($id);
        $buyRequest->delete();
        return $buyRequest;
    }

    public function getBuyRequestData()
    {
        return BuyRequest::select([
            'buy_requests.*',
            DB::raw('customers.name as customer_name')
        ])
            ->leftJoin('customers', 'buy_requests.customer_id', '=', 'customers.id');
    }
}
