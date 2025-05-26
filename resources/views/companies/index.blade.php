@extends('layouts.app')
@section('title')
    {{ __('messages.companies.companies') }}
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
            <h1>{{ __('messages.companies.companies') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('companies.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('companies.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('companies.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('companies.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="companyImportButton">
                    {{ __('messages.common.import') }}
                </button>
                <a href="{{ route('companies.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.companies.add') }}
                </a>
            </div>
        </div>
        <!-- Company Impor tModal Import Modal -->
        <div class="modal fade" id="companyImportModal" tabindex="-1" role="dialog"
            aria-labelledby="companyImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('companies.import') }}" method="POST" enctype="multipart/form-data"
                    id="companyImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="companyImportModalLabel">{{ __('Import Companies via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('companies.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="companyCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="companyCsvFile" required>
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
                    @include('companies.table')
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

        let companiesTable = $('#companiesTable').DataTable({
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
                url: "{{ route('companies.index') }}",
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
                    data: 'name',
                    name: 'name',
                    width: '30%'
                },
                {
                    data: 'description',
                    name: 'description',
                    width: '40%'
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
            let companieId = $(event.currentTarget).data('id');
            deleteItem("{{ route('companies.destroy', ':id') }}".replace(':id', companieId), '#companiesTable',
                "{{ __('messages.companies.companies') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('companies.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('companies.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('companies.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a href="#" title="Delete" class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a href="${viewUrl}" title="View" class="btn btn-info action-btn has-icon view-btn" style="margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="${editUrl}" title="Edit" class="btn btn-warning action-btn has-icon edit-btn" style="margin:2px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>`;
        }
    </script>

    <script>
        $(document).ready(function() {
            // Hide modal initially
            $('#companyImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#companyImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            // Show modal on button click
            $('#companyImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#companyImportModal').modal('show');
                window.manuallyOpenedCompany = true;
            });

            // Focus file input when modal opens
            $('#companyImportModal').on('shown.bs.modal', function() {
                $('#companyCsvFile').focus();
            });

            // Reset form when modal hides
            $('#companyImportModal').on('hidden.bs.modal', function() {
                $('#companyImportForm')[0].reset();
                window.manuallyOpenedCompany = false;
            });

            // Auto-hide if opened unintentionally
            setTimeout(function() {
                if ($('#companyImportModal').hasClass('show') && !window.manuallyOpenedCompany) {
                    $('#companyImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            // Hide modal if clicked outside
            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#companyImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
