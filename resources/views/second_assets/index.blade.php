@extends('layouts.app')
@section('title')
    {{ __('messages.second_assets.second_assets') }}
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
            <h1>{{ __('messages.second_assets.second_assets') }}</h1>
            <div class="section-header-breadcrumb float-right">
                {{-- <div class="card-header-action mr-3 select2-mobile-margin">
                    {{ Form::select('status', [
                        'ready' => 'Ready',
                        'pending' => 'Pending',
                        'undeployable' => 'Undeployable',
                        'archived' => 'Archived',
                        'operational' => 'Operational',
                        'non-operational' => 'Non-Operational',
                        'repairing' => 'Repairing'
                    ], null, ['id' => 'filterStatus', 'class' => 'form-control status-filter', 'placeholder' => 'Filter by Status']) }}
                </div> --}}
            </div>
            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('second-assets.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('second-assets.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('second-assets.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('second-assets.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="secondAssetImportButton">
                    {{ __('messages.common.import') }}
                </button>

                <a href="{{ route('second-assets.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.second_assets.add') }}
                </a>
            </div>
        </div>
        <!-- Second Asset Import Modal -->
        <div class="modal fade" id="secondAssetImportModal" tabindex="-1" role="dialog"
            aria-labelledby="secondAssetImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('second-assets.import') }}" method="POST" enctype="multipart/form-data"
                    id="secondAssetImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="secondAssetImportModalLabel">{{ __('Import Second Assets via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('second-assets.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="secondAssetCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="secondAssetCsvFile"
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
                    @include('second_assets.table')
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

        let secondAssetTable = $('#secondAssetTable').DataTable({
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
                url: "{{ route('second-assets.index') }}",
                data: function (data) {
                    data.status = $('#filterStatus').val();
                }
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                // {
                //     data: 'DT_RowIndex',
                //     name: 'DT_RowIndex',
                //     width: '5%',
                //     orderable: false,
                //     searchable: false
                // },
                {
                    data: 'asset_name',
                    name: 'asset_name',
                    width: '15%'
                },
                {
                    data: 'serial_number',
                    name: 'serial_number',
                    width: '10%'
                },
                {
                    data: 'model',
                    name: 'model',
                    width: '10%'
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '10%',
                    render: function(data) {
                        return data.charAt(0).toUpperCase() + data.slice(1).replace('-', ' ');
                    }
                },
                {
                    data: 'location',
                    name: 'location',
                    width: '10%'
                },
                {
                    data: 'supplier',
                    name: 'supplier',
                    width: '10%'
                },
                {
                    data: 'purchase_date',
                    name: 'purchase_date',
                    width: '10%',
                    render: function(data) {
                        return data ? moment(data).format('YYYY-MM-DD') : '';
                    }
                },
                {
                    data: 'purchase_cost',
                    name: 'purchase_cost',
                    width: '10%',
                    render: function(data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : '$0.00';
                    }
                },
                {
                    data: 'order_number',
                    name: 'order_number',
                    width: '10%'
                },
                {
                    data: 'warranty',
                    name: 'warranty',
                    width: '5%',
                    render: function(data) {
                        return data + ' months';
                    }
                },
                // {
                //     data: 'description',
                //     name: 'description',
                //     width: '15%',
                //     render: function(data) {
                //         return data ? data.substring(0, 50) + (data.length > 50 ? '...' : '') : '';
                //     }
                // },
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
            order: [[1, 'asc']]
        });

        $('#filterStatus').change(function() {
            secondAssetTable.ajax.reload();
        });

        $(document).on('click', '.delete-btn', function(event) {
            let secondAssetId = $(event.currentTarget).data('id');
            deleteItem("{{ route('second-assets.destroy', ':id') }}".replace(':id', secondAssetId), '#secondAssetTable',
                "{{ __('messages.second_assets.second_asset') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('second-assets.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('second-assets.show', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('second-assets.edit', ':id') }}".replace(':id', id);

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
            $('#secondAssetImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#secondAssetImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#secondAssetImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#secondAssetImportModal').modal('show');
                window.manuallyOpenedSecondAsset = true;
            });

            $('#secondAssetImportModal').on('shown.bs.modal', function() {
                $('#secondAssetCsvFile').focus();
            });

            $('#secondAssetImportModal').on('hidden.bs.modal', function() {
                $('#secondAssetImportForm')[0].reset();
                window.manuallyOpenedSecondAsset = false;
            });

            setTimeout(function() {
                if ($('#secondAssetImportModal').hasClass('show') && !window.manuallyOpenedSecondAsset) {
                    $('#secondAssetImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#secondAssetImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
