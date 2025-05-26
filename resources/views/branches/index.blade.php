@extends('layouts.app')
@section('title')
    {{ __('messages.branches.name') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
<style>
    /* Modal styles */
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

    /* Action button styles */
    .action-btn {
        width: 32px !important;
        height: 32px !important;
        padding: 0 !important;
        line-height: 32px !important;
        text-align: center !important;
        border-radius: 4px !important;
        margin: 2px !important;
        float: right !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .action-btn i {
        font-size: 14px !important;
        line-height: 1 !important;
        margin: 0 !important;
    }

    /* Specific button colors */
    .btn-warning.action-btn {
        background-color: #f0ad4e !important;
        border-color: #eea236 !important;
    }

    .btn-info.action-btn {
        background-color: #5bc0de !important;
        border-color: #46b8da !important;
    }

    .btn-danger.action-btn {
        background-color: #d9534f !important;
        border-color: #d43f3a !important;
    }

    /* Button hover effects */
    .action-btn:hover {
        opacity: 0.85 !important;
    }
</style>
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
            <h1>{{ __('messages.branches.name') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            {{-- @can('create_branches')
                <div class="float-right">
                    <a href="{{ route('branches.create') }}" id="btnAdd" class="btn btn-primary form-btn">
                        {{ __('messages.branches.add') }} </a>
                </div>
            @endcan --}}

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('branches.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('branches.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('branches.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('branches.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="branchImportButton">
                    {{ __('messages.common.import') }}
                </button>
                <a href="{{ route('branches.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.branches.add') }}
                </a>
            </div>
        </div>
        <!-- Branch Import Modal -->
        <div class="modal fade" id="branchImportModal" tabindex="-1" role="dialog"
            aria-labelledby="branchImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('branches.import') }}" method="POST" enctype="multipart/form-data"
                    id="branchImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="branchImportModalLabel">{{ __('Import Branches via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('branches.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="branchCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="branchCsvFile" required>
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
                    @if (session()->has('flash_notification'))
                        @foreach (session('flash_notification') as $message)
                            <div class="alert alert-{{ $message['level'] }}">
                                {{ $message['message'] }}
                            </div>
                        @endforeach
                    @endif
                    @include('branches.table')
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        'use strict';


        let tbl = $('#designationTable').DataTable({
            oLanguage: {
                'sEmptyTable': Lang.get('messages.common.no_data_available_in_table'),
                'sInfo': Lang.get('messages.common.data_base_entries'),
                sLengthMenu: Lang.get('messages.common.menu_entry'),
                sInfoEmpty: Lang.get('messages.common.no_entry'),
                sInfoFiltered: Lang.get('messages.common.filter_by'),
                sZeroRecords: Lang.get('messages.common.no_matching'),
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: route('branches.index'),
                dataSrc: function(json) {
                    // Check the number of rows in the response
                    if (json.data.length >= 6) {
                        // Hide the button if there are more than 6 rows
                        $('#btnAdd').hide();
                    } else {
                        // Show the button if there are 6 or fewer rows
                        $('#btnAdd').show();
                    }

                    // Return the data to populate the DataTable
                    return json.data;
                }
            },
            columns: [{
                    data: function(row) {
                        // Decode HTML entities
                        let element = document.createElement('textarea');
                        element.innerHTML = row.company_name ?? '';
                        return element.value; // Decoded plain text
                    },
                    name: 'company_name',
                    width: '15%'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.name;
                    },
                    name: 'name',
                    width: '15%'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.vat_number) {
                            return row.vat_number;
                        }
                        return '';
                    },
                    name: 'vat_number',
                    width: '15%'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.phone) {
                            return row.phone;
                        }
                        return '';
                    },
                    name: 'phone',
                    width: '20%'

                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.country && row.country.name) {
                            return row.country.name;
                        }
                        return '';
                    },
                    name: 'country',
                    orderable: false, // Disable sorting
                    searchable: false // Disable searching
                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.city) {
                            return row.city;
                        }
                        return '';
                    },
                    name: 'city',
                    width: '10%'
                },
                {
                    data: function(row) {
                        return row.bank?.name ?? '';

                    },
                    name: 'bank.name',
                    width: '10%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '25%'
                }
            ],
            responsive: true // Enable responsive features
        });


        $(document).on('click', '.edit-btn', function(event) {
            let did = $(event.currentTarget).data('id');
            const url = route('branches.edit', did);
            window.location.href = url;
        });

        $(document).on('click', '.delete-btn', function(event) {
            let assetCateogryId = $(event.currentTarget).data('id');
            deleteItem(route('branches.destroy', assetCateogryId), '#designationTable',
                '{{ __('messages.branches.name') }}');
        });
    </script>
    <script>
        // Define messages for translations
        var messages = {
            delete: "{{ __('messages.common.delete') }}",
            edit: "{{ __('messages.common.edit') }}",
            view: "{{ __('messages.common.view') }}"
        };

        // Define permissions
        var permissions = {
            updateItem: "{{ auth()->user()->can('update_branches') ? 'true' : 'false' }}",
            deleteItem: "{{ auth()->user()->can('delete_branches') ? 'true' : 'false' }}",
            viewItem: "{{ auth()->user()->can('view_branches') ? 'true' : 'false' }}"
        };

        // Function to render action buttons based on permissions
        function renderActionButtons(id) {
            let buttons = '';



            if (permissions.updateItem === 'true') {
                let editUrl = `{{ route('branches.edit', ':id') }}`;
                editUrl = editUrl.replace(':id', id);
                buttons += `
                <a title="${messages.edit}" href="${editUrl}" class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
            `;
            }
            if (permissions.viewItem === 'true') {
                let viewUrl = `{{ route('branches.view', ':id') }}`;
                viewUrl = viewUrl.replace(':id', id);
                buttons += `
                <a title="${messages.view}" href="${viewUrl}" class="btn btn-info action-btn has-icon view-btn" style="float:right;margin:2px;">
                    <i class="fa fa-eye"></i>
                </a>
            `;
            }

            if (permissions.deleteItem === 'true') {
                buttons += `
                <a title="${messages.delete}" href="#" class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="float:right;margin:2px;">
                    <i class="fa fa-trash"></i>
                </a>
            `;
            }

            return buttons;
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#branchImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#branchImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#branchImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#branchImportModal').modal('show');
                window.manuallyOpenedBranch = true;
            });

            $('#branchImportModal').on('shown.bs.modal', function() {
                $('#branchCsvFile').focus();
            });

            $('#branchImportModal').on('hidden.bs.modal', function() {
                $('#branchImportForm')[0].reset();
                window.manuallyOpenedBranch = false;
            });

            setTimeout(function() {
                if ($('#branchImportModal').hasClass('show') && !window.manuallyOpenedBranch) {
                    $('#branchImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#branchImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
