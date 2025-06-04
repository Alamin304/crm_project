@extends('layouts.app')
@section('title')
    {{ __('messages.accessory.details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.accessory.details') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('accessories.edit', $accessory->id) }}" class="btn btn-primary form-btn">
                    {{ __('messages.common.edit') }}
                </a>
                <a href="{{ route('accessories.index') }}" class="btn btn-primary form-btn ml-2">
                    {{ __('messages.common.back') }}
                </a>
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
                                        <th>{{ __('messages.accessory.accessory_name') }}</th>
                                        <td>{{ $accessory->accessory_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.category_name') }}</th>
                                        <td>{{ $accessory->category_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.supplier') }}</th>
                                        <td>{{ $accessory->supplier }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.manufacturer') }}</th>
                                        <td>{{ $accessory->manufacturer }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.location') }}</th>
                                        <td>{{ $accessory->location ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.model_number') }}</th>
                                        <td>{{ $accessory->model_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.order_number') }}</th>
                                        <td>{{ $accessory->order_number ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.accessory.purchase_cost') }}</th>
                                        <td>{{ $accessory->purchase_cost ? formatCurrency($accessory->purchase_cost) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.purchase_date') }}</th>
                                        <td>{{ $accessory->purchase_date ? formatDate($accessory->purchase_date) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.quantity') }}</th>
                                        <td>{{ $accessory->quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.min_quantity') }}</th>
                                        <td>{{ $accessory->min_quantity }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.accessory.for_sell') }}</th>
                                        <td>{{ $accessory->for_sell ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    @if($accessory->for_sell)
                                    <tr>
                                        <th>{{ __('messages.accessory.selling_price') }}</th>
                                        <td>{{ formatCurrency($accessory->selling_price) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>{{ __('messages.accessory.image') }}</th>
                                        <td>
                                            @if($accessory->image)
                                                <img src="{{ asset('storage/'.$accessory->image) }}" alt="Accessory Image" class="img-thumbnail" style="max-width: 200px;">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>{{ __('messages.accessory.notes') }}:</strong>
                                <div class="border rounded p-3">
                                    {!! $accessory->notes !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
