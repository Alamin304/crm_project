@extends('layouts.print')

@section('content')
    <h1>Work Orders Report</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Work Order</th>
                <th>Start Date</th>
                <th>Work Center</th>
                <th>Manufacturing Order</th>
                <th>Product Quantity</th>
                <th>Unit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workOrders as $workOrder)
            <tr>
                <td>{{ $workOrder->work_order }}</td>
                <td>{{ $workOrder->start_date->format('Y-m-d H:i') }}</td>
                <td>{{ $workOrder->work_center }}</td>
                <td>{{ $workOrder->manufacturing_order }}</td>
                <td>{{ $workOrder->product_quantity }}</td>
                <td>{{ $workOrder->unit }}</td>
                <td>{{ ucfirst($workOrder->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
