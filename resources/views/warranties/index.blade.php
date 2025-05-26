@extends('layouts.app')

@section('title')
    {{ __('messages.warranties.warranties') }}
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

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-processing {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-complete {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-closed {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-canceled {
            background-color: #e2e3e5;
            color: #383d41;
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
            <h1>{{ __('messages.warranties.warranties') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('warranties.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('warranties.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('warranties.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('warranties.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="warrantyImportButton">
                    {{ __('messages.common.import') }}
                </button>
                <div class="float-right">
                    <a href="{{ route('warranties.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.warranties.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- Warranty Import Modal -->
        <div class="modal fade" id="warrantyImportModal" tabindex="-1" role="dialog"
            aria-labelledby="warrantyImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('warranties.import') }}" method="POST" enctype="multipart/form-data"
                    id="warrantyImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="warrantyImportModalLabel">{{ __('Import Warranties via CSV') }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('warranties.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="warrantyCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="warrantyCsvFile"
                                    required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                {{ __('messages.common.import') }}
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
                    @include('warranties.table')
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

        let warrantyCreateUrl = "{{ route('warranties.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: warrantyCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#warrantyTable').DataTable().ajax.reload(null, false);
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

        let warrantyTable = $('#warrantyTable').DataTable({
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
                url: "{{ route('warranties.index') }}",
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
                    data: 'claim_code',
                    name: 'claim_code',
                    width: '15%'
                },
                {
                    data: 'customer',
                    name: 'customer',
                    width: '15%'
                },
                {
                    data: 'description',
                    name: 'description',
                    width: '20%',
                    render: function(data) {
                        return data.length > 50 ? data.substr(0, 50) + '...' : data;
                    }
                },
                {
                    data: 'date_created',
                    name: 'date_created',
                    width: '10%',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD');
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '15%',
                    render: function(data, type, row) {
                        let options = ['approved', 'processing', 'complete', 'closed', 'canceled'];
                        let html = `<select class="form-control status-select" data-id="${row.id}">`;

                        options.forEach(function(option) {
                            let selected = data.toLowerCase() === option ? 'selected' : '';
                            html +=
                                `<option value="${option}" ${selected}>${option.charAt(0).toUpperCase() + option.slice(1)}</option>`;
                        });

                        html += '</select>';
                        return html;
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
            deleteItem("{{ route('warranties.destroy', ['warranty' => ':id']) }}".replace(':id', id),
                '#warrantyTable', "{{ __('messages.warranty.warranty') }}");
        });

        $(document).on('change', '.status-select', function() {
            let id = $(this).data('id');
            let status = $(this).val();

            $.ajax({
                url: "{{ route('warranties.update-status', ['id' => ':id']) }}".replace(':id', id),
                type: 'PUT',
                data: {
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        displaySuccessMessage(response.message);
                        $('#warrantyTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(response) {
                    displayErrorMessage(response.responseJSON.message);
                }
            });
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('warranties.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('warranties.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('warranties.edit', ':id') }}".replace(':id', id);

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
    <script>
        $(document).ready(function() {
            $('#warrantyImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#warrantyImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#warrantyImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#warrantyImportModal').modal('show');
                window.manuallyOpenedWarranty = true;
            });

            $('#warrantyImportModal').on('shown.bs.modal', function() {
                $('#warrantyCsvFile').focus();
            });

            $('#warrantyImportModal').on('hidden.bs.modal', function() {
                $('#warrantyImportForm')[0].reset();
                window.manuallyOpenedWarranty = false;
            });

            setTimeout(function() {
                if ($('#warrantyImportModal').hasClass('show') && !window.manuallyOpenedWarranty) {
                    $('#warrantyImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#warrantyImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
