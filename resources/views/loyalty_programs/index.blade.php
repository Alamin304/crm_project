@extends('layouts.app')
@section('title')
    {{ __('messages.loyalty_programs.loyalty_programs') }}
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

        .rule-container {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .add-rule-btn {
            margin-bottom: 20px;
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

    {{-- Validation Errors --}}
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
            <h1>{{ __('messages.loyalty_programs.loyalty_programs') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('loyalty-programs.export', ['format' => 'pdf']) }}">
                            {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('loyalty-programs.export', ['format' => 'csv']) }}">
                            {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('loyalty-programs.export', ['format' => 'xlsx']) }}">
                            {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('loyalty-programs.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>

                {{-- Import Modal Trigger --}}
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="loyaltyProgramsImportButton">
                    {{ __('Import') }}
                </button>

                <div class="float-right">
                    <a href="{{ route('loyalty-programs.create') }}" class="btn btn-primary btn-sm form-btn">
                        {{ __('messages.loyalty_programs.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- Import Modal -->
        <div class="modal fade" id="loyaltyProgramsImportModal" tabindex="-1" role="dialog"
            aria-labelledby="loyaltyProgramsImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('loyalty-programs.import') }}" method="POST" enctype="multipart/form-data"
                    id="loyaltyProgramsImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="loyaltyProgramsImportModalLabel">
                                {{ __('Import Loyalty Programs via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <a href="{{ route('loyalty-programs.sample-csv') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                                </a>
                            </div>

                            <div class="form-group">
                                <label for="loyaltyProgramsCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="loyaltyProgramsCsvFile"
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
                    @include('loyalty_programs.table')
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

        let loyaltyProgramCreateUrl = "{{ route('loyalty-programs.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: loyaltyProgramCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#loyaltyProgramTable').DataTable().ajax.reload(null, false);
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

        let loyaltyProgramTable = $('#loyaltyProgramTable').DataTable({
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
                url: "{{ route('loyalty-programs.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'name',
                    name: 'name',
                    width: '15%'
                },
                {
                    data: 'redeem_type',
                    name: 'redeem_type',
                    width: '10%'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    width: '10%'
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    width: '10%'
                },
                {
                    data: 'minimum_point_to_redeem',
                    name: 'minimum_point_to_redeem',
                    width: '10%'
                },
                {
                    data: 'rule_base',
                    name: 'rule_base',
                    width: '10%'
                },
                {
                    data: 'minimum_purchase',
                    name: 'minimum_purchase',
                    width: '10%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '15%',
                    orderable: false
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let loyaltyProgramId = $(event.currentTarget).data('id');
            deleteItem("{{ route('loyalty-programs.destroy', ['loyaltyProgram' => ':id']) }}".replace(':id',
                    loyaltyProgramId),
                '#loyaltyProgramTable', "{{ __('messages.loyalty_programs.loyalty_programs') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('loyalty-programs.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('loyalty-programs.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('loyalty-programs.edit', ':id') }}".replace(':id', id);
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

        // Modal handling for import
        $(document).ready(function() {
            $('#loyaltyProgramsImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#loyaltyProgramsImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#loyaltyProgramsImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#loyaltyProgramsImportModal').modal('show');
                window.manuallyOpenedLoyaltyPrograms = true;
            });

            $('#loyaltyProgramsImportModal').on('shown.bs.modal', function() {
                $('#loyaltyProgramsCsvFile').focus();
            });

            $('#loyaltyProgramsImportModal').on('hidden.bs.modal', function() {
                $('#loyaltyProgramsImportForm')[0].reset();
                window.manuallyOpenedLoyaltyPrograms = false;
            });

            setTimeout(function() {
                if ($('#loyaltyProgramsImportModal').hasClass('show') && !window
                    .manuallyOpenedLoyaltyPrograms) {
                    $('#loyaltyProgramsImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#loyaltyProgramsImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
