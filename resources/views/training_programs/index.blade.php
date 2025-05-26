@extends('layouts.app')

@section('title')
    {{ __('messages.training_programs.training_programs') }}
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
            <h1>{{ __('Training Programs') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('training-programs.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('training-programs.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('training-programs.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('training-programs.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                {{-- <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="trainingProgramImportButton">
                    {{ __('messages.common.import') }}
                </button> --}}
                <div class="float-right">
                    <a href="{{ route('training-programs.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.training_programs.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- Training Program Import Modal -->
        {{-- <div class="modal fade" id="trainingProgramImportModal" tabindex="-1" role="dialog"
            aria-labelledby="trainingProgramImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('training-programs.import') }}" method="POST" enctype="multipart/form-data"
                    id="trainingProgramImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="trainingProgramImportModalLabel">
                                {{ __('Import Training Programs via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ asset('sample/training_programs_import_sample.xlsx') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="trainingProgramCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="trainingProgramCsvFile"
                                    required>
                                <small class="form-text text-muted">
                                    Required columns: program_name, training_type, program_items, point, departments,
                                    apply_position, description, staff_name, start_date, finish_date
                                </small>
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
        </div> --}}
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('training_programs.table')
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

        let trainingProgramCreateUrl = "{{ route('training-programs.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: trainingProgramCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#trainingProgramTable').DataTable().ajax.reload(null, false);
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

        let trainingProgramTable = $('#trainingProgramTable').DataTable({
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
                url: "{{ route('training-programs.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'program_name',
                    name: 'program_name',
                    width: '15%'
                },
                {
                    data: 'training_type',
                    name: 'training_type',
                    width: '15%'
                },
                {
                    data: 'description',
                    name: 'description',
                    width: '25%'
                },
                {
                    data: 'point',
                    name: 'point',
                    width: '10%'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    width: '15%'
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
            responsive: true,
            order: [
                [0, 'desc']
            ]
        });

        $(document).on('click', '.delete-btn', function(event) {
            let trainingProgramId = $(event.currentTarget).data('id');
            deleteItem("{{ route('training-programs.destroy', ['trainingProgram' => ':id']) }}".replace(':id',
                    trainingProgramId),
                '#trainingProgramTable', "{{ __('Training Program') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('training-programs.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('training-programs.show', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('training-programs.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">

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
                    <a title="{{ __('messages.common.delete') }}" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            `;
        }

        // Import Modal Handling
        // $(document).ready(function() {
        //     $('#trainingProgramImportModal').modal('hide');
        //     $('.modal').removeClass('show');
        //     $('body').removeClass('modal-open');
        //     $('.modal-backdrop').remove();

        //     $('#trainingProgramImportModal').css({
        //         'display': 'none',
        //         'padding-right': '0px'
        //     });

        //     $('#trainingProgramImportButton').on('click', function(e) {
        //         e.preventDefault();
        //         e.stopPropagation();
        //         $('#trainingProgramImportModal').modal('show');
        //         window.manuallyOpenedTrainingProgram = true;
        //     });

        //     $('#trainingProgramImportModal').on('shown.bs.modal', function() {
        //         $('#trainingProgramCsvFile').focus();
        //     });

        //     $('#trainingProgramImportModal').on('hidden.bs.modal', function() {
        //         $('#trainingProgramImportForm')[0].reset();
        //         window.manuallyOpenedTrainingProgram = false;
        //     });

        //     setTimeout(function() {
        //         if ($('#trainingProgramImportModal').hasClass('show') && !window.manuallyOpenedTrainingProgram) {
        //             $('#trainingProgramImportModal').modal('hide');
        //             $('body').removeClass('modal-open');
        //             $('.modal-backdrop').remove();
        //         }
        //     }, 100);

        //     $(document).on('click', function(e) {
        //         if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
        //             $('#trainingProgramImportModal').modal('hide');
        //         }
        //     });
        // });
    </script>
@endsection
