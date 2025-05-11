@extends('layouts.app')

@section('title')
    {{ __('messages.job_categories.job_categories') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style>
        .export-dropdown {
            min-width: 120px;
        }

        .export-dropdown .dropdown-menu {
            min-width: 160px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.job_categories.job_categories') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.job_categories.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                         <a class="dropdown-item" href="{{ route('job-categories.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('job-categories.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('CSV') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <div class="float-right">
                    <a href="{{ route('job-categories.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.job_categories.add') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('job_categories.table')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-switch.min.js') }}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
@endsection

@section('scripts')
    <script>
        'use strict';

        let table = $('#jobCategoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('job-categories.index') }}",
            },
            columns: [
                { data: 'id', name: 'id', width: '10%' },
                { data: 'name', name: 'name', width: '20%' },
                { data: 'description', name: 'description', width: '20%' },
                { data: 'start_date', name: 'start_date', width: '15%' },
                { data: 'end_date', name: 'end_date', width: '15%' },
                {
                    data: function(row) {
                        return renderActionButtons(row.id, row.status);
                    },
                    name: 'id',
                    width: '20%',
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

        // Initialize switches when table is drawn
        table.on('draw', function () {
            $('.status-toggle').bootstrapToggle({
                on: 'ON',
                off: 'OFF',
                onstyle: 'success',
                offstyle: 'danger',
                size: 'small'
            });
        });

        // Handle status toggle
        $(document).on('change', '.status-toggle', function() {
            let id = $(this).data('id');
            let state = $(this).prop('checked');
            let url = "{{ route('job-categories.status', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                type: 'PUT',
                data: {
                    status: state ? 1 : 0,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(error) {
                    // Revert the toggle if error occurs
                    $(this).bootstrapToggle('toggle');
                    toastr.error('Error updating status');
                }.bind(this)
            });
        });

        // Handle delete
        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            let url = "{{ route('job-categories.destroy', ':id') }}".replace(':id', id);
            deleteItem(url, '#jobCategoryTable', "{{ __('messages.job_categories.job_categories') }}");
        });

        // Render action buttons with toggle switch
        function renderActionButtons(id, status) {
            let deleteUrl = "{{ route('job-categories.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('job-categories.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('job-categories.edit', ':id') }}".replace(':id', id);

            return `
                <div class="d-flex justify-content-end align-items-center">
                    <input type="checkbox" class="status-toggle" data-id="${id}"
                        ${status ? 'checked' : ''} data-toggle="toggle">
                    <a title="{{ __('messages.common.edit') }}" href="${editUrl}"
                        class="btn btn-warning btn-sm action-btn has-icon edit-btn ml-2">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a title="{{ __('messages.common.view') }}" href="${viewUrl}"
                        class="btn btn-info btn-sm action-btn has-icon view-btn ml-1">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="{{ __('messages.common.delete') }}" href="#"
                        class="btn btn-danger btn-sm action-btn has-icon delete-btn ml-1" data-id="${id}">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            `;
        }
    </script>
@endsection

