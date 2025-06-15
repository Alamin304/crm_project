@extends('layouts.print')

@section('content')
    <div class="header">
        <h2>Manufacturing Orders Report</h2>
    </div>
    <div class="date">
        Generated on: {{ now()->format('Y-m-d H:i') }}
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Deadline</th>
                <th>Responsible</th>
                <th>BOM Code</th>
                <th>Routing</th>
            </tr>
        </thead>
        <tbody>
            @foreach($manufacturingOrders as $order)
            <tr>
                <td>{{ $order->product }}</td>
                <td>{{ $order->quantity }}</td>
                <td>{{ $order->unit_of_measure }}</td>
                <td>{{ $order->deadline->format('Y-m-d H:i') }}</td>
                <td>{{ $order->responsible }}</td>
                <td>{{ $order->bom_code }}</td>
                <td>{{ $order->routing }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
