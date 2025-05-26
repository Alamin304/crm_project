@extends('layouts.app')
@section('title')
    {{ __('messages.banks.name') }}
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
            <h1>{{ __('messages.banks.name') }} </h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('banks.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('banks.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('banks.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('banks.export', ['format' => 'print']) }}" target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="bankImportButton">
                    {{ __('messages.common.import') }}
                </button>
                <a href="{{ route('banks.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.banks.add') }}
                </a>
            </div>
            {{-- @can('create_leave_groups')
                <div class="float-right">
                    <a href="{{ route('banks.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.banks.add') }} </a>
                </div>
            @endcan --}}
        </div>
        <!-- Bank Import Modal -->
        <div class="modal fade" id="bankImportModal" tabindex="-1" role="dialog" aria-labelledby="bankImportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('banks.import') }}" method="POST" enctype="multipart/form-data"
                    id="bankImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="bankImportModalLabel">{{ __('Import Banks via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('banks.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="bankCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="bankCsvFile" required>
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
                    @include('banks.table')
                </div>
            </div>
        </div>
    </section>
    @include('banks.templates.templates')
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
                url: route('banks.index'),
                beforeSend: function() {
                    startLoader();
                },
                complete: function() {
                    stopLoader();
                }
            },
            order: [
                [0, 'desc'] // Ordering by the hidden 'created_at' column (index 2) in descending order
            ],
            columnDefs: [{
                targets: 0, // Adjust the index for the hidden 'created_at' column
                orderable: true,
                visible: false, // Hide the 'created_at' column
            }],
            columns: [{
                    data: 'updated_at', // Ensure this matches the data key for created_at in your data source
                    name: 'updated_at'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.name;
                        return element.value;
                    },
                    name: 'name',

                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.account_number ?? '';
                        return element.value;
                    },
                    name: 'account_number',

                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.branch_name ?? '';
                        return element.value;
                    },
                    name: 'branch_name',

                }, {
                    data: function(row) {
                        return row.iban_number ?? '';
                    },
                    name: 'iban_number',

                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row
                            .description; // Assuming your data source has a 'description' field
                        return element.value;
                    },
                    name: 'description',

                },
                {
                    data: function(row) {
                        let element = document.createElement('p');
                        let formattedAmount = parseFloat(row.opening_balance).toFixed(
                            2); // Format the amount to 2 decimal places
                        element.textContent = formattedAmount;
                        element.classList.add('text-right'); // Add class to the span for right-alignment
                        return element.outerHTML;
                    },
                    name: 'opening_balance',

                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',


                }
            ],
            responsive: true // Enable responsive features
        });

        $(document).on('click', '.edit-btn', function(event) {
            let did = $(event.currentTarget).data('id');
            const url = route('banks.edit', did);
            window.location.href = url;
        });
        $(document).on('click', '.delete-btn', function(event) {
            let assetCateogryId = $(event.currentTarget).data('id');
            deleteItem(route('banks.destroy', assetCateogryId), '#designationTable',
                '{{ __('messages.banks.name') }}');
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
            updateItem: "{{ auth()->user()->can('update_banks') ? 'true' : 'false' }}",
            deleteItem: "{{ auth()->user()->can('delete_banks') ? 'true' : 'false' }}",
            viewItem: "{{ auth()->user()->can('view_banks') ? 'true' : 'false' }}"
        };

        // Function to render action buttons based on permissions
        function renderActionButtons(id) {
            let buttons = '';
            if (permissions.updateItem === 'true') {
                let editUrl = `{{ route('banks.edit', ':id') }}`;
                editUrl = editUrl.replace(':id', id);
                buttons += `
                <a title="${messages.edit}" href="${editUrl}" class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
            `;
            }
            if (permissions.viewItem === 'true') {
                let viewUrl = `{{ route('banks.view', ':id') }}`;
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
            $('#bankImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#bankImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#bankImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#bankImportModal').modal('show');
                window.manuallyOpenedBank = true;
            });

            $('#bankImportModal').on('shown.bs.modal', function() {
                $('#bankCsvFile').focus();
            });

            $('#bankImportModal').on('hidden.bs.modal', function() {
                $('#bankImportForm')[0].reset();
                window.manuallyOpenedBank = false;
            });

            setTimeout(function() {
                if ($('#bankImportModal').hasClass('show') && !window.manuallyOpenedBank) {
                    $('#bankImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#bankImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
