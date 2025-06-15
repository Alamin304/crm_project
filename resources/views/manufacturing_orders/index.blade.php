@extends('layouts.app')
@section('title')
    {{ __('messages.manufacturing_orders.manufacturing_orders') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.manufacturing_orders.manufacturing_orders') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('manufacturing-orders.export', ['format' => 'pdf']) }}">
                            {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('manufacturing-orders.export', ['format' => 'csv']) }}">
                            {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('manufacturing-orders.export', ['format' => 'xlsx']) }}">
                            {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('manufacturing-orders.export', ['format' => 'print']) }}" target="_blank">
                            {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>

                <div class="float-right">
                    <a href="{{ route('manufacturing-orders.create') }}" class="btn btn-primary btn-sm form-btn">
                        {{ __('messages.manufacturing_orders.add') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('manufacturing_orders.table')
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

        let manufacturingOrderCreateUrl = "{{ route('manufacturing-orders.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: manufacturingOrderCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#manufacturingOrderTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addNewForm', '#btnSave');
                },
            });
        });

        let manufacturingOrderTable = $('#manufacturingOrderTable').DataTable({
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
                url: "{{ route('manufacturing-orders.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'deadline',
                    name: 'deadline',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD HH:mm');
                    }
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'unit_of_measure',
                    name: 'unit_of_measure'
                },
                {
                    data: 'responsible',
                    name: 'responsible'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '200px',
                    orderable: false
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let manufacturingOrderId = $(event.currentTarget).data('id');
            deleteItem("{{ route('manufacturing-orders.destroy', ['manufacturing_order' => ':id']) }}".replace(':id', manufacturingOrderId),
                '#manufacturingOrderTable', "{{ __('messages.manufacturing_orders.manufacturing_orders') }}");
        });

        // Action buttons rendering
        function renderActionButtons(id) {
            let deleteUrl = "{{ route('manufacturing-orders.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('manufacturing-orders.show', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('manufacturing-orders.edit', ':id') }}".replace(':id', id);
            return `
                <div style="float: right;">
                    <a title="View" href="${viewUrl}"
                       class="btn btn-info action-btn has-icon view-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="Edit" href="${editUrl}"
                       class="btn btn-warning action-btn has-icon edit-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a title="Delete" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            `;
        }
    </script>
@endsection
