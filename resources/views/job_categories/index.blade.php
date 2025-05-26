@extends('layouts.app')

@section('title')
    {{ __('messages.job_categories.job_categories') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style>
        .export-dropdown {
            min-width: 120px;
        }

        .export-dropdown .dropdown-menu {
            min-width: 160px;
        }

        /* Modal styling - fixing z-index and interaction issues */
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

        /* Ensure input fields are clickable */
        .modal input,
        .modal button,
        .modal a {
            position: relative;
            z-index: 2060 !important;
        }
    </style>
@endsection

@section('content')
    <section class="section">
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

        <div class="section-header d-flex justify-content-between align-items-center flex-wrap">
            <h1>{{ __('messages.job_categories.job_categories') }}</h1>

            <div class="d-flex flex-wrap align-items-center">

                {{-- Export Dropdown --}}
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn btn-sm" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.job_categories.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('job-categories.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('job-categories.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('job-categories.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('job-categories.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                    </div>
                </div>

                {{-- Import Modal Trigger - Changed to use ID instead of data attributes --}}
                {{-- <button type="button" class="btn btn-success btn-sm mr-2" id="importButton">
                    {{ __('messages.common.import') }}
                </button> --}}

                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="importButton">
                    {{ __('messages.common.import') }}
                </button>

                {{-- Add Job Category --}}
                <a href="{{ route('job-categories.create') }}" class="btn btn-primary form-btn btn-sm">
                    <i class=""></i> {{ __('messages.job_categories.add') }}
                </a>
            </div>
        </div>

        <!-- Import Modal - Removed data-show attribute if present -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('job-categories.import') }}" method="POST" enctype="multipart/form-data"
                    id="importForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">{{ __('Import Job Categories via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            {{-- Sample Download --}}
                            <div class="mb-3">
                                <a href="{{ route('job-categories.sample-csv') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                                </a>
                            </div>

                            {{-- File Input --}}
                            <div class="form-group">
                                <label for="csvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="csvFile" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                {{ __('messages.common.import') }}
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('job_categories.table')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-switch.min.js') }}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@endsection

@section('scripts')
    <script>
        'use strict';

        let table = $('#jobCategoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('job-categories.index') }}",
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '10%',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    width: '20%'
                },
                {
                    data: 'description',
                    name: 'description',
                    width: '20%'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    width: '15%'
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    width: '15%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id, row.status);
                    },
                    name: 'id',
                    width: '20%',
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                'emptyTable': "{{ __('messages.common.no_data_available_in_table') }}",
                'info': "{{ __('messages.common.data_base_entries') }}",
                'lengthMenu': "{{ __('messages.common.menu_entry') }}",
                'infoEmpty': "{{ __('messages.common.no_entry') }}",
                'infoFiltered': "{{ __('messages.common.filter_by') }}",
                'zeroRecords': "{{ __('messages.common.no_matching') }}",
            },
            responsive: true
        });

        // Initialize switches when table is drawn
        table.on('draw', function() {
            $('.status-toggle').bootstrapToggle({
                on: 'ON',
                off: 'OFF',
                onstyle: 'success',
                offstyle: 'danger',
                size: 'small'
            });
        });

        // Handle status toggle
        $(document).on('change', '.status-toggle', function() {
            let id = $(this).data('id');
            let state = $(this).prop('checked');
            let url = "{{ route('job-categories.status', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                type: 'PUT',
                data: {
                    status: state ? 1 : 0,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(error) {
                    // Revert the toggle if error occurs
                    $(this).bootstrapToggle('toggle');
                    toastr.error('Error updating status');
                }.bind(this)
            });
        });

        // Handle delete
        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            let url = "{{ route('job-categories.destroy', ':id') }}".replace(':id', id);
            deleteItem(url, '#jobCategoryTable', "{{ __('messages.job_categories.job_categories') }}");
        });

        // Render action buttons with toggle switch
        function renderActionButtons(id, status) {
            let deleteUrl = "{{ route('job-categories.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('job-categories.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('job-categories.edit', ':id') }}".replace(':id', id);

            return `
                <div class="d-flex justify-content-end align-items-center">

                    <input type="checkbox" class="status-toggle" data-id="${id}"
                        ${status ? 'checked' : ''} data-toggle="toggle">
                        <a title="{{ __('messages.common.delete') }}" href="#"
                        class="btn btn-danger btn-sm action-btn has-icon delete-btn ml-1" data-id="${id}">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a title="{{ __('messages.common.edit') }}" href="${editUrl}"
                        class="btn btn-warning btn-sm action-btn has-icon edit-btn ml-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a title="{{ __('messages.common.view') }}" href="${viewUrl}"
                        class="btn btn-info btn-sm action-btn has-icon view-btn ml-1">
                        <i class="fas fa-eye"></i>
                    </a>

                </div>
            `;
        }

        // Modal handling - FIXED VERSION
        $(document).ready(function() {
            // 1. Make sure modal is hidden on page load
            $('#importModal').modal('hide');

            // 2. Remove any modal-related classes that might cause auto-opening
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            // 3. Reset modal CSS properties
            $('#importModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            // 4. Set up proper event handling for the Import button
            $('#importButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Show modal manually
                $('#importModal').modal('show');

                // Flag to indicate modal was manually opened
                window.manuallyOpened = true;
            });

            // 5. Ensure modal interactions work properly
            $('#importModal').on('shown.bs.modal', function() {
                // Focus on file input when modal is shown
                $('#csvFile').focus();
            });

            // 6. Handle modal closing properly
            $('#importModal').on('hidden.bs.modal', function() {
                // Reset form when modal is closed
                $('#importForm')[0].reset();

                // Reset manual opening flag
                window.manuallyOpened = false;
            });

            // 7. Force close any auto-opened modal after a short delay
            setTimeout(function() {
                if ($('#importModal').hasClass('show') && !window.manuallyOpened) {
                    $('#importModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            // 8. Handle outside modal clicks
            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#importModal').modal('hide');
                }
            });
        });
    </script>
@endsection
