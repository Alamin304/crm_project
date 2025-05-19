@extends('layouts.app')

@section('title')
    {{ __('messages.booking_sources.booking_sources') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .modal-backdrop {
            display: none !important;
        }

        body.modal-open {
            overflow: auto !important;
            padding-right: 0 !important;
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-dialog {
            margin-top: 10vh;
            z-index: 2050 !important;
        }

        .modal-content {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        .modal input,
        .modal button,
        .modal a {
            position: relative;
            z-index: 2060 !important;
        }
    </style>
@endsection

@section('content')
    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors (for row-level import validation failures) --}}
    @if (session()->has('failures'))
        <div class="alert alert-danger">
            <strong>Import failed due to the following row errors:</strong>
            <ul>
                @foreach (session()->get('failures') as $failure)
                    <li>
                        Row {{ $failure->row() }}:
                        @foreach ($failure->errors() as $error)
                            {{ $error }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
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
                <button type="button" class="btn btn-success btn-sm form-btn mr-2" id="bookingSourceImportButton">
                    <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
                </button>
                <div class="float-right">
                    <a href="{{ route('booking-sources.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.booking_sources.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- BookingSource Import Modal -->
        <div class="modal fade" id="bookingSourceImportModal" tabindex="-1" role="dialog"
            aria-labelledby="bookingSourceImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('booking-sources.import') }}" method="POST" enctype="multipart/form-data"
                    id="bookingSourceImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bookingSourceImportModalLabel">
                                {{ __('Import Booking Sources via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('booking-sources.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="bookingSourceCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="bookingSourceCsvFile"
                                    required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
                            </button>
                            <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">{{ __('Cancel') }}</button>
                        </div>
                    </div>
                </form>
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
    <script>
        $(document).ready(function() {
            $('#bookingSourceImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#bookingSourceImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#bookingSourceImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#bookingSourceImportModal').modal('show');
                window.manuallyOpenedBookingSource = true;
            });

            $('#bookingSourceImportModal').on('shown.bs.modal', function() {
                $('#bookingSourceCsvFile').focus();
            });

            $('#bookingSourceImportModal').on('hidden.bs.modal', function() {
                $('#bookingSourceImportForm')[0].reset();
                window.manuallyOpenedBookingSource = false;
            });

            setTimeout(function() {
                if ($('#bookingSourceImportModal').hasClass('show') && !window
                    .manuallyOpenedBookingSource) {
                    $('#bookingSourceImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#bookingSourceImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
