@extends('layouts.app')

@section('title')
    {{ __('messages.warranties.warranty_information') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.warranties.warranty_information') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('warranties.info.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('warranties.info.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('warranties.info.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('warranties.info.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="warrantyInfoTable">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.warranties.id') }}</th>
                                    {{-- <th>{{ __('messages.warranties.date_created') }}</th> --}}
                                    <th>{{ __('messages.warranties.customer') }}</th>
                                    <th>{{ __('messages.warranties.order_number') }}</th>
                                    <th>{{ __('messages.warranties.invoice') }}</th>
                                    <th>{{ __('messages.warranties.product_service_name') }}</th>
                                    <th>{{ __('messages.warranties.rate') }}</th>
                                    <th>{{ __('messages.warranties.quantity') }}</th>
                                    <th>{{ __('messages.warranties.serial_number') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        'use strict';

        let warrantyInfoTable = $('#warrantyInfoTable').DataTable({
            oLanguage: {
                'sEmptyTable': "{{ __('messages.common.no_data_available_in_table') }}",
                'sInfo': "{{ __('messages.common.data_base_entries') }}",
                sLengthMenu: "{{ __('messages.common.menu_entry') }}",
                sInfoEmpty: "{{ __('messages.common.no_entry') }}",
                sInfoFiltered: "{{ __('messages.common.filter_by') }}",
                sZeroRecords: "{{ __('messages.common.no_matching') }}",
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('warranties.information') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                // { data: 'date_created', name: 'date_created' },
                {
                    data: 'customer',
                    name: 'customer'
                },
                {
                    data: null,
                    name: 'Oreder Number',
                    orderable: false,
                    searchable: false,
                    render: function() {
                        return '-'; // or return some default
                    }
                },
                {
                    data: 'invoice',
                    name: 'invoice'
                },
                {
                    data: 'product_service_name',
                    name: 'product_service_name'
                },
                {
                    data: null,
                    name: 'rate',
                    orderable: false,
                    searchable: false,
                    render: function() {
                        return '-'; // or return some default
                    }
                },
                {
                    data: null,
                    name: 'quantity',
                    orderable: false,
                    searchable: false,
                    render: function() {
                        return '-';
                    }
                },
                {
                    data: null,
                    name: 'serial_number',
                    orderable: false,
                    searchable: false,
                    render: function() {
                        return '-';
                    }
                }
            ],
            responsive: true
        });
    </script>
@endsection
