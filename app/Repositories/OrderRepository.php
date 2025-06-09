<?php
namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function create(array $input)
    {
        return Order::create($input);
    }

    public function update(array $input, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($input);
        return $order;
    }
}
