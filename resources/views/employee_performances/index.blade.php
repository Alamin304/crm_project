@extends('layouts.app')

@section('title')
    {{ __('messages.employee_performances.employee_performances') }}
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
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.employee_performances.employee_performances') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('employee_performances.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('employee_performances.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('employee_performances.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('employee_performances.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <a href="{{ route('employee_performances.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.employee_performances.add') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('employee_performances.table')
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

        let performanceTable = $('#employeePerformanceTable').DataTable({
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
                url: "{{ route('employee_performances.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    width: '10%'
                },
                {
                    data: 'employee_name',
                    name: 'employees.name',
                    width: '30%'
                },
                {
                    data: 'total_score',
                    name: 'total_score',
                    width: '20%'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    width: '20%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '20%',
                    orderable: false,
                    searchable: false
                }
            ],
            responsive: true
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('employee_performances.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('employee_performances.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('employee_performances.edit', ':id') }}".replace(':id', id);

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

        // Delete button click handler
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            let deleteUrl = "{{ route('employee_performances.destroy', ':id') }}".replace(':id', id);

            if (confirm('Are you sure you want to delete this performance record?')) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            performanceTable.ajax.reload();
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Failed to delete');
                    }
                });
            }
        });
    </script>
@endsection
