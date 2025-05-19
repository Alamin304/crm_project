@extends('layouts.app')
@section('title')
    {{ __('messages.wake_up_calls.wake_up_calls') }}
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
            <h1>{{ __('messages.wake_up_calls.wake_up_calls') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.wake_up_calls.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('wakeUpCalls.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('wakeUpCalls.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('wakeUpCalls.export', ['format' => 'xlsx']) }}">
                            <i class="fas fa-file-excel text-primary mr-2"></i> {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('wakeUpCalls.export', ['format' => 'print']) }}"
                            target="_blank">
                            <i class="fas fa-print text-info mr-2"></i> {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm form-btn mr-2" id="wakeUpCallImportButton">
                    <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
                </button>
                <div class="float-right">
                    <a href="{{ route('wake_up_calls.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.wake_up_calls.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- WakeUpCall Import Modal -->
        <div class="modal fade" id="wakeUpCallImportModal" tabindex="-1" role="dialog"
            aria-labelledby="wakeUpCallImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('wake-up-calls.import') }}" method="POST" enctype="multipart/form-data"
                    id="wakeUpCallImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="wakeUpCallImportModalLabel">
                                {{ __('Import Wake-Up Calls via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('wake-up-calls.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="wakeUpCallCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="wakeUpCallCsvFile"
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
                    @include('wake_up_calls.table')
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

        let wakeUpCallCreateUrl = "{{ route('wake_up_calls.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: wakeUpCallCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#wakeUpCallTable').DataTable().ajax.reload(null, false);
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

        let wakeUpCallTable = $('#wakeUpCallTable').DataTable({
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
                url: "{{ route('wake_up_calls.index') }}",
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
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.customer_name;
                        return element.value;
                    },
                    name: 'customer_name',
                    width: '20%'
                },
                {
                    data: 'date',
                    name: 'date',
                    width: '20%'
                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.description;
                        return element.value;
                    },
                    name: 'description',
                    width: '30%'
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
            let id = $(event.currentTarget).data('id');
            deleteItem("{{ route('wake_up_calls.destroy', ['wakeUpCall' => ':id']) }}".replace(':id', id),
                '#wakeUpCallTable', "{{ __('messages.wake_up_calls.wake_up_calls') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('wake_up_calls.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('wake_up_calls.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('wake_up_calls.edit', ':id') }}".replace(':id', id);

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
            $('#wakeUpCallImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#wakeUpCallImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#wakeUpCallImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#wakeUpCallImportModal').modal('show');
                window.manuallyOpenedWakeUpCall = true;
            });

            $('#wakeUpCallImportModal').on('shown.bs.modal', function() {
                $('#wakeUpCallCsvFile').focus();
            });

            $('#wakeUpCallImportModal').on('hidden.bs.modal', function() {
                $('#wakeUpCallImportForm')[0].reset();
                window.manuallyOpenedWakeUpCall = false;
            });

            setTimeout(function() {
                if ($('#wakeUpCallImportModal').hasClass('show') && !window.manuallyOpenedWakeUpCall) {
                    $('#wakeUpCallImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#wakeUpCallImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
