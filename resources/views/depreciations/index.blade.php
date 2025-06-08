@extends('layouts.app')
@section('title')
    {{ __('messages.depreciation.depreciations') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.depreciation.depreciations') }}</h1>
            <div class="section-header-breadcrumb float-right">
            </div>
            <div class="float-right d-flex">
                <a href="{{ route('depreciations.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.depreciation.add') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <table class="table table-responsive-sm table-responsive-md table-responsive-lg table-striped table-bordered"
                           id="depreciationTable">
                        <thead>
                        <tr>
                            <th>{{ __('messages.depreciation.image') }}</th>
                            <th>{{ __('messages.depreciation.asset_name') }}</th>
                            <th>{{ __('messages.depreciation.serial_no') }}</th>
                            <th>{{ __('messages.depreciation.depreciation_name') }}</th>
                            <th>{{ __('messages.depreciation.status') }}</th>
                            <th>{{ __('messages.depreciation.purchase_date') }}</th>
                            <th>{{ __('messages.depreciation.EOL_date') }}</th>
                            <th>{{ __('messages.depreciation.cost') }}</th>
                            <th>{{ __('messages.depreciation.current_value') }}</th>
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

        let depreciationTable = $('#depreciationTable').DataTable({
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
                url: "{{ route('depreciations.index') }}"
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                {
                    data: 'image',
                    name: 'image',
                    width: '5%',
                    render: function(data) {
                        return data ?
                            `<img src="${window.location.origin}/storage/${data}" class="depreciation-img" alt="Depreciation Image" width="50">` :
                            `<img src="${window.location.origin}/assets/img/default-depreciation.png" class="depreciation-img" alt="Default Image" width="50">`;
                    },
                    orderable: false
                },
                {
                    data: 'asset_name',
                    name: 'asset_name',
                    width: '15%'
                },
                {
                    data: 'serial_no',
                    name: 'serial_no',
                    width: '10%'
                },
                {
                    data: 'depreciation_name',
                    name: 'depreciation_name',
                    width: '15%'
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '10%',
                    render: function(data) {
                        let statusClass = '';
                        switch(data) {
                            case 'ready':
                                statusClass = 'badge-success';
                                break;
                            case 'operational':
                                statusClass = 'badge-primary';
                                break;
                            case 'pending':
                            case 'repairing':
                                statusClass = 'badge-warning';
                                break;
                            case 'non-operational':
                            case 'undeployable':
                                statusClass = 'badge-danger';
                                break;
                            case 'archive':
                                statusClass = 'badge-secondary';
                                break;
                            default:
                                statusClass = 'badge-info';
                        }
                        return `<span class="badge ${statusClass}">${data}</span>`;
                    }
                },
                {
                    data: 'purchase_date',
                    name: 'purchase_date',
                    width: '10%'
                },
                {
                    data: 'EOL_date',
                    name: 'EOL_date',
                    width: '10%'
                },
                {
                    data: 'cost',
                    name: 'cost',
                    width: '10%',
                    render: function(data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : '$0.00';
                    }
                },
                {
                    data: 'current_value',
                    name: 'current_value',
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
            let depreciationId = $(event.currentTarget).data('id');
            deleteItem("{{ route('depreciations.destroy', ':id') }}".replace(':id', depreciationId),
                '#depreciationTable', "{{ __('messages.depreciation.depreciation') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('depreciations.destroy', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a href="#" title="Delete" class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>`;
        }
    </script>
@endsection
