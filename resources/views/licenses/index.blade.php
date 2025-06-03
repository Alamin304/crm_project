@extends('layouts.app')
@section('title')
    {{ __('messages.licenses.licenses') }}
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
            <h1>{{ __('messages.licenses.licenses') }}</h1>
            <div class="section-header-breadcrumb float-right">
                {{-- <div class="card-header-action mr-3 select2-mobile-margin">
                    {{ Form::select('category', $categories, null, ['id' => 'filterCategory', 'class' => 'form-control', 'placeholder' => 'Filter by Category']) }}
                </div>
                <div class="card-header-action mr-3">
                    {{ Form::select('manufacturer', $manufacturers, null, ['id' => 'filterManufacturer', 'class' => 'form-control', 'placeholder' => 'Filter by Manufacturer']) }}
                </div> --}}
            </div>
            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('licenses.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('licenses.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('licenses.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('licenses.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="licenseImportButton">
                    {{ __('messages.common.import') }}
                </button>

                <a href="{{ route('licenses.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.licenses.add') }}
                </a>
            </div>
        </div>
        <!-- License Import Modal -->
        <div class="modal fade" id="licenseImportModal" tabindex="-1" role="dialog"
            aria-labelledby="licenseImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('licenses.import') }}" method="POST" enctype="multipart/form-data"
                    id="licenseImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="licenseImportModalLabel">{{ __('Import Licenses via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('licenses.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="licenseCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="licenseCsvFile"
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
                    <table class="table table-responsive-sm table-responsive-md table-responsive-lg table-striped table-bordered"
                        id="licenseTable">
                        <thead>
                            <tr>
                                <th>{{ __('messages.licenses.software_name') }}</th>
                                <th>{{ __('messages.licenses.category_name') }}</th>
                                <th>{{ __('messages.licenses.product_key') }}</th>
                                <th>{{ __('messages.licenses.seats') }}</th>
                                <th>{{ __('messages.licenses.manufacturer') }}</th>
                                <th>{{ __('messages.licenses.purchase_date') }}</th>
                                <th>{{ __('messages.licenses.expiration_date') }}</th>
                                <th>{{ __('messages.licenses.purchase_cost') }}</th>
                                <th>{{ __('messages.common.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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

        let licenseTable = $('#licenseTable').DataTable({
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
                url: "{{ route('licenses.index') }}",
                data: function (data) {
                    data.category = $('#filterCategory').val();
                    data.manufacturer = $('#filterManufacturer').val();
                }
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                {
                    data: 'software_name',
                    name: 'software_name',
                    width: '15%'
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    width: '10%'
                },
                {
                    data: 'product_key',
                    name: 'product_key',
                    width: '10%'
                },
                {
                    data: 'seats',
                    name: 'seats',
                    width: '5%'
                },
                {
                    data: 'manufacturer',
                    name: 'manufacturer',
                    width: '10%'
                },
                {
                    data: 'purchase_date',
                    name: 'purchase_date',
                    width: '10%'
                },
                {
                    data: 'expiration_date',
                    name: 'expiration_date',
                    width: '10%',
                    render: function(data) {
                        return data ? data : 'N/A';
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
            let licenseId = $(event.currentTarget).data('id');
            deleteItem("{{ route('licenses.destroy', ':id') }}".replace(':id', licenseId), '#licenseTable',
                "{{ __('messages.licenses.license') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('licenses.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('licenses.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('licenses.edit', ':id') }}".replace(':id', id);

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

        $('#filterCategory, #filterManufacturer').change(function() {
            licenseTable.ajax.reload();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#licenseImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#licenseImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#licenseImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#licenseImportModal').modal('show');
                window.manuallyOpenedLicense = true;
            });

            $('#licenseImportModal').on('shown.bs.modal', function() {
                $('#licenseCsvFile').focus();
            });

            $('#licenseImportModal').on('hidden.bs.modal', function() {
                $('#licenseImportForm')[0].reset();
                window.manuallyOpenedLicense = false;
            });

            setTimeout(function() {
                if ($('#licenseImportModal').hasClass('show') && !window.manuallyOpenedLicense) {
                    $('#licenseImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#licenseImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection