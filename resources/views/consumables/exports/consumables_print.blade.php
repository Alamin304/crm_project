@extends('layouts.app')
@section('title')
    Consumables Print
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h1>Consumables List</h1>
                        <div>
                            <a href="#" class="btn btn-primary" onclick="window.print();">Print</a>
                            <a href="{{ route('consumables.index') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0">Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
                                    <th>Manufacturer</th>
                                    <th>Quantity</th>
                                    <th>Min Qty</th>
                                    <th>Purchase Cost</th>
                                    <th>Purchase Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consumables as $consumable)
                                <tr>
                                    <td>{{ $consumable->consumable_name }}</td>
                                    <td>{{ $consumable->category_name }}</td>
                                    <td>{{ $consumable->supplier }}</td>
                                    <td>{{ $consumable->manufacturer }}</td>
                                    <td>{{ $consumable->quantity }}</td>
                                    <td>{{ $consumable->min_quantity }}</td>
                                    <td>{{ number_format($consumable->purchase_cost, 2) }}</td>
                                    <td>{{ $consumable->purchase_date->format('Y-m-d') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
