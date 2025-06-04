@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Accessories Report</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Accessory Name</th>
                    <th>Category</th>
                    <th>Manufacturer</th>
                    <th>Quantity</th>
                    <th>Purchase Cost</th>
                    <th>Purchase Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accessories as $accessory)
                <tr>
                    <td>{{ $accessory->accessory_name }}</td>
                    <td>{{ $accessory->category_name }}</td>
                    <td>{{ $accessory->manufacturer }}</td>
                    <td>{{ $accessory->quantity }}</td>
                    <td>{{ $accessory->purchase_cost ? '$'.number_format($accessory->purchase_cost, 2) : 'N/A' }}</td>
                    <td>{{ $accessory->purchase_date ? $accessory->purchase_date->format('Y-m-d') : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
