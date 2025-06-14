@extends('layouts.app')
@section('title')
    Work Centers Print
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                        <h1>Work Centers</h1>
                        <div>
                            <i class="fas fa-print cursor-pointer" onclick="window.print();"></i>
                        </div>
                    </div>
                    <div class="mb-5">
                        <p class="mb-0">Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Working Hours</th>
                                    <th>Time Efficiency (%)</th>
                                    <th>Cost Per Hour</th>
                                    <th>Capacity</th>
                                    <th>OEE Target (%)</th>
                                    <th>Time Before Prod (m)</th>
                                    <th>Time After Prod (m)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workCenters as $workCenter)
                                <tr>
                                    <td>{{ $workCenter->name }}</td>
                                    <td>{{ $workCenter->code }}</td>
                                    <td>{{ $workCenter->working_hours }}</td>
                                    <td>{{ $workCenter->time_efficiency }}</td>
                                    <td>${{ number_format($workCenter->cost_per_hour, 2) }}</td>
                                    <td>{{ $workCenter->capacity }}</td>
                                    <td>{{ $workCenter->oee_target }}</td>
                                    <td>{{ $workCenter->time_before_prod }}</td>
                                    <td>{{ $workCenter->time_after_prod }}</td>
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
