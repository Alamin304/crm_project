@extends('layouts.app')
@section('title')
    {{ __('messages.currencies.currencies') }}
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
            <h1>{{ __('messages.currencies.currencies') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            {{-- @can('create_currencies')
                <div class="float-right">
                    <a href="{{ route('currencies.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.currencies.add') }} </a>
                </div>
            @endcan --}}
            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('currencies.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('currencies.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('currencies.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('currencies.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="currencyImportButton">
                    {{ __('messages.common.import') }}
                </button>
                <a href="{{ route('currencies.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.currencies.add') }}
                </a>
            </div>

        </div>
        <!-- Currency Import Modal -->
        <div class="modal fade" id="currencyImportModal" tabindex="-1" role="dialog"
            aria-labelledby="currencyImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('currencies.import') }}" method="POST" enctype="multipart/form-data"
                    id="currencyImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="currencyImportModalLabel">{{ __('Import Currencies via CSV') }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('currencies.download-sample') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="currencyCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="currencyCsvFile"
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
                    @if (session()->has('flash_notification'))
                        @foreach (session('flash_notification') as $message)
                            <div class="alert alert-{{ $message['level'] }}">
                                {{ $message['message'] }}
                            </div>
                        @endforeach
                    @endif
                    @include('currencies.table')
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

        let designationCreateUrl = route('currencies.store');
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');


            let description = $('<div />').
            html($('#createDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#createDescription').summernote('isEmpty')) {
                $('#createDescription').val('');
            } else if (empty) {
                displayErrorMessage(
                    'Description field is not contain only white space');
                processingBtn('#addNewForm', '#btnSave', 'reset');
                return false;
            }
            $.ajax({
                url: designationCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#designation_name').val('');
                        const url = route('currencies.index', );
                        window.location.href = url;
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
                url: route('currencies.index'),
            },
            columns: [{
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.name;
                        return element.value;
                    },
                    name: 'name',
                    width: '30%'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row
                            .description; // Assuming your data source has a 'description' field
                        return element.value;
                    },
                    name: 'description',
                    width: '50%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '200px'
                }
            ],
            responsive: true // Enable responsive features
        });

        $(document).on('click', '.delete-btn', function(event) {
            let assetCateogryId = $(event.currentTarget).data('id');
            deleteItem(route('currencies.destroy', assetCateogryId), '#designationTable',
                '{{ __('messages.currencies.name') }}');
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
            updateItem: "{{ auth()->user()->can('update_currencies') ? 'true' : 'false' }}",
            deleteItem: "{{ auth()->user()->can('delete_currencies') ? 'true' : 'false' }}",
            viewItem: "{{ auth()->user()->can('view_currencies') ? 'true' : 'false' }}"
        };
        // Function to render action buttons based on permissions
        function renderActionButtons(id) {
            let buttons = '';
            if (permissions.updateItem === 'true') {
                let editUrl = `{{ route('currencies.edit', ':id') }}`;
                editUrl = editUrl.replace(':id', id);
                buttons += `
                <a title="${messages.edit}" href="${editUrl}" class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
            `;
            }
            if (permissions.viewItem === 'true') {
                let viewUrl = `{{ route('currencies.view', ':id') }}`;
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
            $('#currencyImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#currencyImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#currencyImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#currencyImportModal').modal('show');
                window.manuallyOpenedCurrency = true;
            });

            $('#currencyImportModal').on('shown.bs.modal', function() {
                $('#currencyCsvFile').focus();
            });

            $('#currencyImportModal').on('hidden.bs.modal', function() {
                $('#currencyImportForm')[0].reset();
                window.manuallyOpenedCurrency = false;
            });

            setTimeout(function() {
                if ($('#currencyImportModal').hasClass('show') && !window.manuallyOpenedCurrency) {
                    $('#currencyImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#currencyImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
