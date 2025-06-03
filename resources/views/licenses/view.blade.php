@extends('layouts.app')
@section('title')
    {{ __('messages.licenses.details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.licenses.details') }}</h1>
            <div class="section-header-breadcrumb float-right">
                {{-- <a href="{{ route('licenses.edit', $license->id) }}"
                   class="btn btn-primary form-btn mr-2">{{ __('messages.common.edit') }}</a> --}}
                <a href="{{ route('licenses.index') }}"
                   class="btn btn-primary form-btn">{{ __('messages.licenses.list') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.licenses.software_name') }}</th>
                                        <td>{{ $license->software_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.category_name') }}</th>
                                        <td>{{ $license->category_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.product_key') }}</th>
                                        <td>{{ $license->product_key }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.seats') }}</th>
                                        <td>{{ $license->seats }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.manufacturer') }}</th>
                                        <td>{{ $license->manufacturer }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.licensed_name') }}</th>
                                        <td>{{ $license->licensed_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.licensed_email') }}</th>
                                        <td>{{ $license->licensed_email }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.reassignable') }}</th>
                                        <td>{{ $license->reassignable ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.supplier') }}</th>
                                        <td>{{ $license->supplier }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.order_number') }}</th>
                                        <td>{{ $license->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.purchase_order_number') }}</th>
                                        <td>{{ $license->purchase_order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.purchase_cost') }}</th>
                                        <td>{{ number_format($license->purchase_cost, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.purchase_date') }}</th>
                                        <td>{{ $license->purchase_date->format('Y-m-d') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.expiration_date') }}</th>
                                        <td>{{ $license->expiration_date ? $license->expiration_date->format('Y-m-d') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.termination_date') }}</th>
                                        <td>{{ $license->termination_date ? $license->termination_date->format('Y-m-d') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.depreciation') }}</th>
                                        <td>{{ $license->depreciation }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.maintained') }}</th>
                                        <td>{{ $license->maintained ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.licenses.for_sell') }}</th>
                                        <td>{{ $license->for_sell ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    @if($license->for_sell)
                                        <tr>
                                            <th>{{ __('messages.licenses.selling_price') }}</th>
                                            <td>{{ number_format($license->selling_price, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>{{ __('messages.licenses.notes') }}</th>
                                        <td>{!! $license->notes !!}</td>
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