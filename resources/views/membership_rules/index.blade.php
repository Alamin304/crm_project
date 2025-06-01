@extends('layouts.app')
@section('title')
    {{ __('messages.membership_rules.membership_rules') }}
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
            <h1>{{ __('messages.membership_rules.membership_rules') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('membership-rules.export', ['format' => 'pdf']) }}">
                            {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('membership-rules.export', ['format' => 'csv']) }}">
                            {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('membership-rules.export', ['format' => 'xlsx']) }}">
                            {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('membership-rules.export', ['format' => 'print']) }}" target="_blank">
                            {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>

                {{-- Membership Rules Import Modal Trigger --}}
                <button type="button" class="btn btn-primary btn-sm form-btn mr-2" id="membershipRulesImportButton">
                    {{ __('Import') }}
                </button>

                <div class="float-right">
                    <a href="{{ route('membership-rules.create') }}" class="btn btn-primary btn-sm form-btn">
                        {{ __('messages.membership_rules.add') }}
                    </a>
                </div>
            </div>
        </div>
        <!-- Membership Rules Import Modal -->
        <div class="modal fade" id="membershipRulesImportModal" tabindex="-1" role="dialog" aria-labelledby="membershipRulesImportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('membership-rules.import') }}" method="POST" enctype="multipart/form-data" id="membershipRulesImportForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="membershipRulesImportModalLabel">{{ __('Import Membership Rules via CSV') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <a href="{{ route('membership-rules.sample-csv') }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                                </a>
                            </div>

                            <div class="form-group">
                                <label for="membershipRulesCsvFile">{{ __('Upload CSV File') }}</label>
                                <input type="file" name="file" class="form-control-file" id="membershipRulesCsvFile" required>
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
                    @include('membership_rules.table')
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

        let membershipRuleCreateUrl = "{{ route('membership-rules.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: membershipRuleCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#membershipRuleTable').DataTable().ajax.reload(null, false);
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

        let membershipRuleTable = $('#membershipRuleTable').DataTable({
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
                url: "{{ route('membership-rules.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                {
                    data: 'name',
                    name: 'name',
                    width: '15%'
                },
                {
                    data: 'customer_group',
                    name: 'customer_group',
                    width: '15%'
                },
                {
                    data: 'customer',
                    name: 'customer',
                    width: '15%'
                },
                {
                    data: 'card',
                    name: 'card',
                    width: '15%'
                },
                {
                    data: 'point_from',
                    name: 'point_from',
                    width: '10%'
                },
                {
                    data: 'point_to',
                    name: 'point_to',
                    width: '10%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '20%',
                    orderable: false
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let membershipRuleId = $(event.currentTarget).data('id');
            deleteItem("{{ route('membership-rules.destroy', ['membership_rule' => ':id']) }}".replace(':id', membershipRuleId),
                '#membershipRuleTable', "{{ __('messages.membership_rules.membership_rules') }}");
        });

        // Action buttons rendering
        function renderActionButtons(id) {
            let deleteUrl = "{{ route('membership-rules.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('membership-rules.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('membership-rules.edit', ':id') }}".replace(':id', id);
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
        // Modal handling for Membership Rules import
        $(document).ready(function() {
            $('#membershipRulesImportModal').modal('hide');
            $('.modal').removeClass('show');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            $('#membershipRulesImportModal').css({
                'display': 'none',
                'padding-right': '0px'
            });

            $('#membershipRulesImportButton').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('#membershipRulesImportModal').modal('show');
                window.manuallyOpenedMembershipRules = true;
            });

            $('#membershipRulesImportModal').on('shown.bs.modal', function() {
                $('#membershipRulesCsvFile').focus();
            });

            $('#membershipRulesImportModal').on('hidden.bs.modal', function() {
                $('#membershipRulesImportForm')[0].reset();
                window.manuallyOpenedMembershipRules = false;
            });

            setTimeout(function() {
                if ($('#membershipRulesImportModal').hasClass('show') && !window.manuallyOpenedMembershipRules) {
                    $('#membershipRulesImportModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            }, 100);

            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                    $('#membershipRulesImportModal').modal('hide');
                }
            });
        });
    </script>
@endsection
