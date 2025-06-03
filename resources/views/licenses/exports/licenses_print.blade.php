@extends('layouts.print')

@section('title')
    Licenses Report
@endsection

@section('content')
    <h1 class="text-center">Licenses Report</h1>
    <p class="text-center">Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Software Name</th>
                <th>Category</th>
                <th>Product Key</th>
                <th>Seats</th>
                <th>Manufacturer</th>
                <th>Purchase Date</th>
                <th>Expiration Date</th>
                <th>Purchase Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($licenses as $license)
                <tr>
                    <td>{{ $license->software_name }}</td>
                    <td>{{ $license->category_name }}</td>
                    <td>{{ $license->product_key }}</td>
                    <td>{{ $license->seats }}</td>
                    <td>{{ $license->manufacturer }}</td>
                    <td>{{ $license->purchase_date->format('Y-m-d') }}</td>
                    <td>{{ $license->expiration_date ? $license->expiration_date->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ number_format($license->purchase_cost, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection