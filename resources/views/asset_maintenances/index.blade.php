@extends('layouts.app')
@section('title')
    {{ __('messages.asset_maintenance.asset_maintenances') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.asset_maintenance.asset_maintenances') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('asset-maintenances.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('asset-maintenances.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('asset-maintenances.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('asset-maintenances.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <a href="{{ route('asset-maintenances.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.asset_maintenance.add') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('asset_maintenances.table')
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
        let assetMaintenanceTable = $('#assetMaintenanceTable').DataTable({
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
                url: "{{ route('asset-maintenances.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                {
                    data: 'asset.name',
                    name: 'asset.name',
                    width: '15%'
                },
                {
                    data: 'maintenance_type',
                    name: 'maintenance_type',
                    width: '10%'
                },
                {
                    data: 'title',
                    name: 'title',
                    width: '15%'
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    width: '10%',
                    render: function(data) {
                        return moment(data).format('YYYY-MM-DD');
                    }
                },
                {
                    data: 'completion_date',
                    name: 'completion_date',
                    width: '10%',
                    render: function(data) {
                        return data ? moment(data).format('YYYY-MM-DD') : 'N/A';
                    }
                },
                {
                    data: 'cost',
                    name: 'cost',
                    width: '10%',
                    render: function(data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : 'N/A';
                    }
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '15%',
                    orderable: false
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let assetMaintenanceId = $(event.currentTarget).data('id');
            deleteItem("{{ route('asset-maintenances.destroy', ':id') }}".replace(':id', assetMaintenanceId),
                '#assetMaintenanceTable', "{{ __('messages.asset_maintenance.asset_maintenance') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('asset-maintenances.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('asset-maintenances.show', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('asset-maintenances.edit', ':id') }}".replace(':id', id);

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
@endsection
