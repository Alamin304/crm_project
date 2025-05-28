@extends('layouts.app')

@section('title')
    {{ __('messages.real_estate_agents.real_estate_agents') }}
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

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(30px);
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
            <h1>{{ __('messages.real_estate_agents.real_estate_agents') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.real_estate_agents.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('real_estate_agents.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('real_estate_agents.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('real_estate_agents.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('real_estate_agents.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                {{-- <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="propertyOwnerImportButton">
                    {{ __('messages.common.import') }}
                </button> --}}
                <div class="float-right">
                    <a href="{{ route('real_estate_agents.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.real_estate_agents.add') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Property Owner Import Modal -->
        <div class="modal fade" id="propertyOwnerImportModal" tabindex="-1" role="dialog"
            aria-labelledby="propertyOwnerImportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                {{-- <form action="{{ route('real_estate_agents.import') }}" method="POST" enctype="multipart/form-data"
                    id="propertyOwnerImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="propertyOwnerImportModalLabel">{{ __('Import Property Owners via CSV') }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <a href="{{ route('real_estate_agents.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                                <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                            </a>

                            <div class="form-group">
                                <label for="propertyOwnerCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="propertyOwnerCsvFile"
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
                </form> --}}
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('real_estate_agents.table')
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

        let propertyOwnerCreateUrl = "{{ route('real_estate_agents.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: propertyOwnerCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#realEstateAgentsTable').DataTable().ajax.reload(null, false);
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

        let realEstateAgentsTable = $('#realEstateAgentsTable').DataTable({
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
                url: "{{ route('real_estate_agents.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'profile_image',
                    name: 'profile_image',
                    width: '10%',
                    render: function(data) {
                        return data ?
                            `<img src="{{ asset('storage/${data}') }}" class="profile-image" alt="Profile Image">` :
                            `<img src="{{ asset('assets/img/default-user.png') }}" class="profile-image" alt="Default Image">`;
                    },
                    orderable: false
                },
                {
                    data: 'code',
                    name: 'code',
                    width: '10%'
                },
                {
                    data: 'owner_name',
                    name: 'owner_name',
                    width: '12%'
                },
                {
                    data: 'email',
                    name: 'email',
                    width: '15%'
                },
                {
                    data: 'phone_number',
                    name: 'phone_number',
                    width: '10%'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    width: '10%',
                    render: function(data, type, row) {
                        let checked = data ? 'checked' : '';
                        return `
                            <label class="switch">
                                <input type="checkbox" class="status-toggle" ${checked} data-id="${row.id}">
                                <span class="slider"></span>
                            </label>
                        `;
                    },
                    orderable: false
                },
                {
                    data: 'verification_status',
                    name: 'verification_status',
                    width: '10%'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    width: '10%',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD');
                    }
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '13%',
                    orderable: false
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            deleteItem("{{ route('real_estate_agents.destroy', ['realEstateAgent' => ':id']) }}".replace(':id',
                    id),
                '#realEstateAgentsTable', "{{ __('messages.real_estate_agents.real_estate_agents') }}");
        });

        $(document).on('change', '.status-toggle', function() {
            let id = $(this).data('id');
            let isActive = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('real_estate_agents.update-status', ['realEstateAgent' => ':id']) }}"
                    .replace(
                        ':id', id),
                type: 'PUT',
                data: {
                    is_active: isActive,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        displaySuccessMessage(response.message);
                        $('#realEstateAgentsTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(response) {
                    displayErrorMessage(response.responseJSON.message);
                    // Revert the toggle if there's an error
                    $(this).prop('checked', !isActive);
                }
            });
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('real_estate_agents.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('real_estate_agents.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('real_estate_agents.edit', ':id') }}".replace(':id', id);

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
    </script>

    <script>
        $(document).ready(function() {
            $('#propertyOwnerImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#propertyOwnerImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#propertyOwnerImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#propertyOwnerImportModal').modal('show');
                window.manuallyOpenedPropertyOwner = true;
            });

            $('#propertyOwnerImportModal').on('shown.bs.modal', function() {
                $('#propertyOwnerCsvFile').focus();
            });

            $('#propertyOwnerImportModal').on('hidden.bs.modal', function() {
                $('#propertyOwnerImportForm')[0].reset();
                window.manuallyOpenedPropertyOwner = false;
            });

            setTimeout(function() {
                if ($('#propertyOwnerImportModal').hasClass('show') && !window
                    .manuallyOpenedPropertyOwner) {
                    $('#propertyOwnerImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#propertyOwnerImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
