@extends('layouts.app')
@section('title')
    {{ __('messages.manufacturing_orders.view') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.manufacturing_orders.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('manufacturing-orders.index') }}"
                   class="btn btn-primary form-btn">{{ __('messages.manufacturing_orders.list') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.product') }}</th>
                                        <td>{{ $manufacturingOrder->product }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.quantity') }}</th>
                                        <td>{{ $manufacturingOrder->quantity }} {{ $manufacturingOrder->unit_of_measure }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.deadline') }}</th>
                                        <td>{{ \Carbon\Carbon::parse($manufacturingOrder->deadline)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.plan_from') }}</th>
                                        <td>{{ \Carbon\Carbon::parse($manufacturingOrder->plan_from)->format('Y-m-d H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.responsible') }}</th>
                                        <td>{{ $manufacturingOrder->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.bom_code') }}</th>
                                        <td>{{ $manufacturingOrder->bom_code }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.reference_code') }}</th>
                                        <td>{{ $manufacturingOrder->reference_code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.manufacturing_orders.routing') }}</th>
                                        <td>{{ $manufacturingOrder->routing }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
