@extends('layouts.app')
@section('title')
    {{ __('messages.consumables.details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.consumables.details') }}</h1>
            <div class="section-header-breadcrumb">
                {{-- <a href="{{ route('consumables.edit', $consumable->id) }}" class="btn btn-primary form-btn float-right">
                    {{ __('messages.common.edit') }}
                </a> --}}
                <a href="{{ route('consumables.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.common.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.consumables.consumable_name') }}</th>
                                        <td>{{ $consumable->consumable_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.category_name') }}</th>
                                        <td>{{ $consumable->category_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.supplier') }}</th>
                                        <td>{{ $consumable->supplier }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.manufacturer') }}</th>
                                        <td>{{ $consumable->manufacturer }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.location') }}</th>
                                        <td>{{ $consumable->location ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.model_number') }}</th>
                                        <td>{{ $consumable->model_number ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.consumables.order_number') }}</th>
                                        <td>{{ $consumable->order_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.purchase_cost') }}</th>
                                        <td>{{ number_format($consumable->purchase_cost, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.purchase_date') }}</th>
                                        <td>{{ $consumable->purchase_date->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.quantity') }}</th>
                                        <td>{{ $consumable->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.min_quantity') }}</th>
                                        <td>{{ $consumable->min_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.consumables.for_sell') }}</th>
                                        <td>{{ $consumable->for_sell ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    @if($consumable->for_sell)
                                    <tr>
                                        <th>{{ __('messages.consumables.selling_price') }}</th>
                                        <td>{{ number_format($consumable->selling_price, 2) }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <strong>{{ __('messages.consumables.notes') }}:</strong>
                                <div class="border p-3 rounded">
                                    {!! $consumable->notes !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($consumable->image)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <strong>{{ __('messages.consumables.image') }}:</strong>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$consumable->image) }}" alt="Consumable Image" width="200">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
