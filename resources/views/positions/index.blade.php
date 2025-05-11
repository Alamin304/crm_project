@extends('layouts.app')

@section('title')
    {{ __('messages.positions.positions') }}
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
        {{-- <div class="section-header item-align-right">
            <h1>{{ __('messages.positions.positions') }}</h1>
            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.positions.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('CSV') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>

                <div class="float-right">
                    <a href="{{ route('positions.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.positions.add_positions') }}
                    </a>
                </div>
            </div>
        </div> --}}

        <div class="section-header item-align-right">
             <h1>{{ __('messages.positions.positions') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.positions.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                            <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'pdf']) }}">
                                <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('PDF') }}
                            </a>
                            <a class="dropdown-item" href="{{ route('positions.export', ['format' => 'csv']) }}">
                                <i class="fas fa-file-csv text-success mr-2"></i> {{ __('CSV') }}
                            </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <div class="float-right">
                    <a href="{{ route('positions.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.positions.add') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('positions.table')
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
@endsection

@section('scripts')
    <script>
        'use strict';

        let table = $('#positionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('positions.index') }}",
            },
            columns: [
                { data: 'id', name: 'id', width: '10%' },
                { data: 'name', name: 'name', width: '25%' },
                {
                    data: function(row) {
                        return row.status ? `<span class="badge badge-success">Active</span>` :
                                            `<span class="badge badge-danger">Inactive</span>`;
                    },
                    name: 'status',
                    width: '15%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '15%',
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

        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            let url = "{{ route('positions.destroy', ':id') }}".replace(':id', id);
            deleteItem(url, '#positionTable', "{{ __('messages.positions.positions') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('positions.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('positions.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('positions.edit', ':id') }}".replace(':id', id);

            return `
                 <div style="float: right;">
                    <a title="{{ __('messages.common.delete') }}" href="#"
                    class="btn btn-danger action-btn has-icon delete-btn"
                    data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a title="{{ __('messages.common.view') }}" href="${viewUrl}"
                    class="btn btn-info action-btn has-icon view-btn"
                    style="float:right;margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="{{ __('messages.common.edit') }}" href="${editUrl}"
                    class="btn btn-warning action-btn has-icon edit-btn"
                    style="float:right;margin:2px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
        }

    </script>
@endsection
