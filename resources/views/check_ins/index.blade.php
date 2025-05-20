@extends('layouts.app')
@section('title')
    {{ __('messages.check_in.check_in_list') }}
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
            <h1>{{ __('messages.check_in.check_in_list') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.check_in.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('check_ins.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('check_ins.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('check_ins.export', ['format' => 'xlsx']) }}">
                            <i class="fas fa-file-excel text-primary mr-2"></i> {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('check_ins.export', ['format' => 'print']) }}"
                            target="_blank">
                            <i class="fas fa-print text-info mr-2"></i> {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm form-btn mr-2" id="checkInImportButton">
                    <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
                </button>
                <div class="float-right">
                    <a href="{{ route('check_ins.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.check_in.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- CheckIn Import Modal -->
        <div class="modal fade" id="checkInImportModal" tabindex="-1" role="dialog"
            aria-labelledby="checkInImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('check_ins.import') }}" method="POST" enctype="multipart/form-data"
                    id="checkInImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="checkInImportModalLabel">{{ __('Import Check-Ins via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('check_ins.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="checkInCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="checkInCsvFile" required>
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
                    @include('check_ins.table')
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

        let checkInCreateUrl = "{{ route('check_ins.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: checkInCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#checkInTable').DataTable().ajax.reload(null, false);
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

        let checkInTable = $('#checkInTable').DataTable({
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
                url: "{{ route('check_ins.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '5%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'booking_number',
                    name: 'booking_number',
                    title: "{{ __('messages.check_in.booking_number') }}",
                    width: '15%'
                },
                {
                    data: 'room_type',
                    name: 'room_type',
                    title: "{{ __('messages.check_in.room_type') }}",
                    width: '15%'
                },
                {
                    data: 'room_no',
                    name: 'room_no',
                    title: "{{ __('messages.check_in.room_no') }}",
                    width: '10%'
                },
                {
                    data: 'check_in',
                    name: 'check_in',
                    title: "{{ __('messages.check_in.check_in') }}",
                    width: '15%'
                },
                {
                    data: 'check_out',
                    name: 'check_out',
                    title: "{{ __('messages.check_in.check_out') }}",
                    width: '15%'
                },
                {
                    data: 'booking_status',
                    name: 'booking_status',
                    title: "{{ __('messages.check_in.booking_status') }}",
                    width: '10%',
                    render: function(data) {
                        return data ? "{{ __('messages.check_in.success') }}" :
                            "{{ __('messages.check_in.pending') }}";
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
            deleteItem("{{ route('check_ins.destroy', ':id') }}".replace(':id', id),
                '#checkInTable', "{{ __('messages.check_in.check_in_list') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('check_ins.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('check_ins.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('check_ins.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a title="{{ __('messages.common.delete') }}" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a title="{{ __('messages.check_in.view') }}" href="${viewUrl}"
                       class="btn btn-info action-btn has-icon view-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="{{ __('messages.check_in.edit') }}" href="${editUrl}"
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
            $('#checkInImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#checkInImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#checkInImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#checkInImportModal').modal('show');
                window.manuallyOpenedCheckIn = true;
            });

            $('#checkInImportModal').on('shown.bs.modal', function() {
                $('#checkInCsvFile').focus();
            });

            $('#checkInImportModal').on('hidden.bs.modal', function() {
                $('#checkInImportForm')[0].reset();
                window.manuallyOpenedCheckIn = false;
            });

            setTimeout(function() {
                if ($('#checkInImportModal').hasClass('show') && !window.manuallyOpenedCheckIn) {
                    $('#checkInImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#checkInImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
