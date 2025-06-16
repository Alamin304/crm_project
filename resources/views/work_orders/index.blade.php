@extends('layouts.app')
@section('title')
    {{ __('Work Orders') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('Work Orders') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('work_orders.export', ['format' => 'pdf']) }}">
                            {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('work_orders.export', ['format' => 'csv']) }}">
                            {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('work_orders.export', ['format' => 'xlsx']) }}">
                            {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('work_orders.export', ['format' => 'print']) }}" target="_blank">
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
                    @include('work_orders.table')
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

        let workOrderTable = $('#workOrderTable').DataTable({
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
                url: "{{ route('work_orders.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                {
                    data: 'work_order',
                    name: 'work_order',
                    width: '15%'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    width: '15%'
                },
                {
                    data: 'work_center',
                    name: 'work_center',
                    width: '15%'
                },
                {
                    data: 'manufacturing_order',
                    name: 'manufacturing_order',
                    width: '15%'
                },
                {
                    data: 'product_quantity',
                    name: 'product_quantity',
                    width: '10%'
                },
                {
                    data: 'unit',
                    name: 'unit',
                    width: '10%'
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '10%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '10%',
                    orderable: false
                }
            ],
            responsive: true
        });

        // Action buttons rendering
        function renderActionButtons(id) {
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
    </script>
@endsection
