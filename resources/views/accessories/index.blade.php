@extends('layouts.app')
@section('title')
    {{ __('messages.accessory.accessories') }}
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

        .accessory-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
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
            <h1>{{ __('messages.accessory.accessories') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('accessories.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('accessories.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('accessories.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('accessories.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="accessoryImportButton">
                    {{ __('messages.common.import') }}
                </button>

                <a href="{{ route('accessories.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.accessory.add') }}
                </a>
            </div>
        </div>

        <!-- Accessory Import Modal -->
        <div class="modal fade" id="accessoryImportModal" tabindex="-1" role="dialog"
            aria-labelledby="accessoryImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('accessories.import') }}" method="POST" enctype="multipart/form-data"
                    id="accessoryImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="accessoryImportModalLabel">{{ __('Import Accessories via CSV') }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('accessories.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="accessoryCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="accessoryCsvFile"
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
                    <table
                        class="table table-responsive-sm table-responsive-md table-responsive-lg table-striped table-bordered"
                        id="accessoryTable">
                        <thead>
                            <tr>
                                <th>{{ __('messages.accessory.image') }}</th>
                                <th>{{ __('messages.accessory.accessory_name') }}</th>
                                <th>{{ __('messages.accessory.category_name') }}</th>
                                <th>{{ __('messages.accessory.manufacturer') }}</th>
                                <th>{{ __('messages.accessory.location') }}</th>
                                <th>{{ __('messages.accessory.model_number') }}</th>
                                <th>{{ __('messages.accessory.purchase_cost') }}</th>
                                <th>{{ __('messages.accessory.purchase_date') }}</th>
                                <th>{{ __('messages.accessory.quantity') }}</th>
                                <th>{{ __('messages.accessory.min_quantity') }}</th>
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

        let accessoryTable = $('#accessoryTable').DataTable({
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
                url: "{{ route('accessories.index') }}",
                data: function(data) {
                    data.category = $('#filterCategory').val();
                    data.manufacturer = $('#filterManufacturer').val();
                }
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'image',
                    name: 'image',
                    width: '5%',
                    render: function(data) {
                        return data ?
                            `<img src="${window.location.origin}/uploads/accessories/${data}" class="accessory-img" alt="Accessory Image">` :
                            `<img src="${window.location.origin}/assets/img/default-accessory.png" class="accessory-img" alt="Default Image">`;
                    },
                    orderable: false
                },

                {
                    data: 'accessory_name',
                    name: 'accessory_name',
                    width: '15%'
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    width: '10%'
                },
                {
                    data: 'manufacturer',
                    name: 'manufacturer',
                    width: '10%'
                },
                {
                    data: 'location',
                    name: 'location',
                    width: '10%'
                },
                {
                    data: 'model_number',
                    name: 'model_number',
                    width: '10%'
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
                    data: 'purchase_date',
                    name: 'purchase_date',
                    width: '10%'
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                    width: '5%'
                },
                {
                    data: 'min_quantity',
                    name: 'min_quantity',
                    width: '5%',
                    render: function(data, type, row) {
                        if (parseInt(row.quantity) <= parseInt(data)) {
                            return `<span class="text-danger">${data}</span>`;
                        }
                        return data;
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
            responsive: true,
            createdRow: function(row, data, dataIndex) {
                if (parseInt(data.quantity) <= parseInt(data.min_quantity)) {
                    $(row).addClass('table-danger');
                }
            }
        });

        $(document).on('click', '.delete-btn', function(event) {
            let accessoryId = $(event.currentTarget).data('id');
            deleteItem("{{ route('accessories.destroy', ':id') }}".replace(':id', accessoryId), '#accessoryTable',
                "{{ __('messages.accessories.accessory') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('accessories.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('accessories.show', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('accessories.edit', ':id') }}".replace(':id', id);

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
            accessoryTable.ajax.reload();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#accessoryImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#accessoryImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#accessoryImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#accessoryImportModal').modal('show');
                window.manuallyOpenedAccessory = true;
            });

            $('#accessoryImportModal').on('shown.bs.modal', function() {
                $('#accessoryCsvFile').focus();
            });

            $('#accessoryImportModal').on('hidden.bs.modal', function() {
                $('#accessoryImportForm')[0].reset();
                window.manuallyOpenedAccessory = false;
            });

            setTimeout(function() {
                if ($('#accessoryImportModal').hasClass('show') && !window.manuallyOpenedAccessory) {
                    $('#accessoryImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#accessoryImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
