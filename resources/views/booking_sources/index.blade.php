@extends('layouts.app')

@section('title')
    {{ __('messages.booking_sources.booking_sources') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .export-dropdown {
            min-width: 120px;
        }

        .export-dropdown .dropdown-menu {
            min-width: 160px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.booking_sources.booking_sources') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('booking-sources.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('booking-sources.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('booking-sources.export', ['format' => 'xlsx']) }}">
                            <i class="fas fa-file-excel text-primary mr-2"></i> {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('booking-sources.export', ['format' => 'print']) }}"
                            target="_blank">
                            <i class="fas fa-print text-info mr-2"></i> {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <div class="float-right">
                    <a href="{{ route('booking-sources.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.booking_sources.add') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('booking_sources.table')
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

        let bookingSourceCreateUrl = "{{ route('booking-sources.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: bookingSourceCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#bookingSourceTable').DataTable().ajax.reload(null, false);
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

        let bookingSourceTable = $('#bookingSourceTable').DataTable({
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
                url: "{{ route('booking-sources.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '10%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'booking_type',
                    name: 'booking_type',
                    width: '30%'
                },
                {
                    data: 'booking_source',
                    name: 'booking_source',
                    width: '40%'
                },
                {
                    data: 'commission_rate',
                    name: 'commission_rate',
                    width: '10%',
                    render: function(data, type, row) {
                        return data + '%';
                    }
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

        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            deleteItem("{{ route('booking-sources.destroy', ['bookingSource' => ':id']) }}".replace(':id', id),
                '#bookingSourceTable', "{{ __('messages.booking_sources.booking_sources') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('booking-sources.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('booking-sources.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('booking-sources.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a title="Delete" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
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
                </div>
            `;
        }
    </script>
@endsection
