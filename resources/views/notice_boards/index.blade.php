@extends('layouts.app')

@section('title')
    {{ __('messages.notice_boards.notice_boards') }}
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
            <h1>{{ __('messages.notice_boards.notice_boards') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.notice_boards.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('notice-boards.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('notice-boards.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('notice-boards.export', ['format' => 'xlsx']) }}">
                            <i class="fas fa-file-excel text-primary mr-2"></i> {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('notice-boards.export', ['format' => 'print']) }}"
                            target="_blank">
                            <i class="fas fa-print text-info mr-2"></i> {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm form-btn mr-2" id="noticeBoardImportButton">
                    <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
                </button>
                <div class="float-right">
                    <a href="{{ route('notice-boards.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.notice_boards.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- Notice Board Import Modal -->
        <div class="modal fade" id="noticeBoardImportModal" tabindex="-1" role="dialog"
            aria-labelledby="noticeBoardImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('notice-boards.import') }}" method="POST" enctype="multipart/form-data"
                    id="noticeBoardImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="noticeBoardImportModalLabel">
                                {{ __('Import Notice Boards via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('notice-boards.download-sample') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="noticeBoardCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="noticeBoardCsvFile"
                                    required>
                                <small class="form-text text-muted">
                                    Required columns: notice_type, description, notice_date, notice_by
                                </small>
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
                    @include('notice_boards.table')
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

        let noticeBoardCreateUrl = "{{ route('notice-boards.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: noticeBoardCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#noticeBoardTable').DataTable().ajax.reload(null, false);
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

        let noticeBoardTable = $('#noticeBoardTable').DataTable({
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
                url: "{{ route('notice-boards.index') }}",
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
                    data: 'notice_type',
                    name: 'notice_type',
                    width: '15%'
                },
                {
                    data: 'description',
                    name: 'description',
                    width: '30%'
                },
                {
                    data: 'notice_date',
                    name: 'notice_date',
                    width: '15%'
                },
                {
                    data: 'notice_by',
                    name: 'notice_by',
                    width: '15%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '20%',
                    orderable: false
                }
            ],
            responsive: true,
            order: [
                [0, 'desc']
            ]
        });

        $(document).on('click', '.delete-btn', function(event) {
            let noticeBoardId = $(event.currentTarget).data('id');
            deleteItem("{{ route('notice-boards.destroy', ['noticeBoard' => ':id']) }}".replace(':id',
                    noticeBoardId),
                '#noticeBoardTable', "{{ __('messages.notice_boards.notice_boards') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('notice-boards.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('notice-boards.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('notice-boards.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a title="{{ __('messages.common.delete') }}" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a title="{{ __('messages.common.view') }}" href="${viewUrl}"
                       class="btn btn-info action-btn has-icon view-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="{{ __('messages.common.edit') }}" href="${editUrl}"
                       class="btn btn-warning action-btn has-icon edit-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
        }

        // Import Modal Handling
        $(document).ready(function() {
            $('#noticeBoardImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#noticeBoardImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#noticeBoardImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#noticeBoardImportModal').modal('show');
                window.manuallyOpenedNoticeBoard = true;
            });

            $('#noticeBoardImportModal').on('shown.bs.modal', function() {
                $('#noticeBoardCsvFile').focus();
            });

            $('#noticeBoardImportModal').on('hidden.bs.modal', function() {
                $('#noticeBoardImportForm')[0].reset();
                window.manuallyOpenedNoticeBoard = false;
            });

            setTimeout(function() {
                if ($('#noticeBoardImportModal').hasClass('show') && !window.manuallyOpenedNoticeBoard) {
                    $('#noticeBoardImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#noticeBoardImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
