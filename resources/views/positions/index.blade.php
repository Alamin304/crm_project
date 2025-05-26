@extends('layouts.app')

@section('title')
    {{ __('messages.positions.positions') }}
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
            <h1>{{ __('messages.positions.positions') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.positions.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="positionImportButton">
                    {{ __('messages.common.import') }}
                </button>
                <div class="float-right">
                    <a href="{{ route('positions.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.positions.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- Position Import Modal -->
        <div class="modal fade" id="positionImportModal" tabindex="-1" role="dialog"
            aria-labelledby="positionImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('positions.import') }}" method="POST" enctype="multipart/form-data"
                    id="positionImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="positionImportModalLabel">{{ __('Import Positions via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('positions.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="positionCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="positionCsvFile"
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
                    @include('positions.table')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
@endsection

@section('scripts')
    <script>
        'use strict';

        let table = $('#positionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('positions.index') }}",
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
                    width: '25%'
                },
                {
                    data: function(row) {
                        return row.status ? `<span class="badge badge-success">Active</span>` :
                            `<span class="badge badge-danger">Inactive</span>`;
                    },
                    name: 'status',
                    width: '15%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '15%',
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

        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            let url = "{{ route('positions.destroy', ':id') }}".replace(':id', id);
            deleteItem(url, '#positionTable', "{{ __('messages.positions.positions') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('positions.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('positions.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('positions.edit', ':id') }}".replace(':id', id);

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
    </script>
    <script>
        $(document).ready(function() {
            $('#positionImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#positionImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#positionImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#positionImportModal').modal('show');
                window.manuallyOpenedPosition = true;
            });

            $('#positionImportModal').on('shown.bs.modal', function() {
                $('#positionCsvFile').focus();
            });

            $('#positionImportModal').on('hidden.bs.modal', function() {
                $('#positionImportForm')[0].reset();
                window.manuallyOpenedPosition = false;
            });

            setTimeout(function() {
                if ($('#positionImportModal').hasClass('show') && !window.manuallyOpenedPosition) {
                    $('#positionImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#positionImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
