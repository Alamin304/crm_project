
@extends('layouts.app')
@section('title')
    {{ __('Orders') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('Orders') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('orders.export', ['format' => 'pdf']) }}">
                            {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('orders.export', ['format' => 'csv']) }}">
                            {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('orders.export', ['format' => 'xlsx']) }}">
                            {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('orders.export', ['format' => 'print']) }}" target="_blank">
                            {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('orders.table')
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

        let orderTable = $('#orderTable').DataTable({
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
                url: "{{ route('orders.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                { data: 'order_number', name: 'order_number' },
                { data: 'order_date', name: 'order_date' },
                { data: 'customer', name: 'customer' },
                { data: 'order_type', name: 'order_type' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'status', name: 'status' },
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div style="float: right;">
                                <a title="View" href="#"
                                   class="btn btn-info action-btn has-icon view-btn"
                                   style="float:right;margin:2px;">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let orderId = $(event.currentTarget).data('id');
            deleteItem("{{ route('orders.destroy', ['order' => ':id']) }}".replace(':id', orderId),
                '#orderTable', "{{ __('Order') }}");
        });
    </script>
@endsection
