@extends('layouts.print')

@section('title')
    Asset Maintenances Report
@endsection

@section('content')
    <h1 class="text-center">Asset Maintenances Report</h1>
    <p class="text-center">Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Asset</th>
                <th>Supplier</th>
                <th>Maintenance Type</th>
                <th>Title</th>
                <th>Start Date</th>
                <th>Completion Date</th>
                <th>Warranty Improvement</th>
                <th>Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assetMaintenances as $maintenance)
            <tr>
                <td>{{ $maintenance->asset->name }}</td>
                <td>{{ $maintenance->supplier->name }}</td>
                <td>{{ $maintenance->maintenance_type }}</td>
                <td>{{ $maintenance->title }}</td>
                <td>{{ $maintenance->start_date->format('Y-m-d') }}</td>
                <td>{{ $maintenance->completion_date ? $maintenance->completion_date->format('Y-m-d') : 'N/A' }}</td>
                <td>{{ $maintenance->warranty_improvement ? 'Yes' : 'No' }}</td>
                <td>{{ $maintenance->cost ? '$' . number_format($maintenance->cost, 2) : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
