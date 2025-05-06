@extends('layouts.app')
@section('title')
    {{ __('messages.booking_lists.booking_lists') }}
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
            <h1>{{ __('messages.booking_lists.booking_lists') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.booking_lists.export') }}
                    </button>
                    {{-- <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('booking-lists.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('booking-lists.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('messages.common.csv') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div> --}}
                </div>
                <div class="float-right">
                    <a href="{{ route('booking_lists.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.booking_lists.add') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('booking_lists.table')
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

        let bookingListCreateUrl = "{{ route('booking_lists.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: bookingListCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#bookingListTable').DataTable().ajax.reload(null, false);
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

        let bookingListTable = $('#bookingListTable').DataTable({
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
                url: "{{ route('booking_lists.index') }}",
            },
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            columns: [
                { data: 'id', name: 'id', title: "{{ __('messages.booking_lists.id') }}", width: '5%' },
                { data: 'booking_number', name: 'booking_number', title: "{{ __('messages.booking_lists.booking_number') }}", width: '15%' },
                { data: 'room_type', name: 'room_type', title: "{{ __('messages.booking_lists.room_type') }}", width: '15%' },
                { data: 'room_no', name: 'room_no', title: "{{ __('messages.booking_lists.room_no') }}", width: '10%' },
                { data: 'check_in', name: 'check_in', title: "{{ __('messages.booking_lists.check_in') }}", width: '15%' },
                { data: 'check_out', name: 'check_out', title: "{{ __('messages.booking_lists.check_out') }}", width: '15%' },
                {
                    data: 'booking_status',
                    name: 'booking_status',
                    title: "{{ __('messages.booking_lists.booking_status') }}",
                    width: '10%',
                    render: function (data) {
                        return data ? "{{ __('messages.booking_lists.success') }}" : "{{ __('messages.booking_lists.pending') }}";
                    }
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    title: "{{ __('messages.common.action') }}",
                    width: '15%',
                    orderable: false
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            deleteItem("{{ route('booking_lists.destroy', ':id') }}".replace(':id', id),
                '#bookingListTable', "{{ __('messages.booking_lists.booking_lists') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('booking_lists.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('booking_lists.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('booking_lists.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a title="{{ __('messages.common.delete') }}" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a title="{{ __('messages.booking_lists.view') }}" href="${viewUrl}"
                       class="btn btn-info action-btn has-icon view-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="{{ __('messages.booking_lists.edit') }}" href="${editUrl}"
                       class="btn btn-warning action-btn has-icon edit-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
        }
    </script>
@endsection
