@extends('layouts.app')
@section('title')
    {{ __('Unaccepted Assets') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('Unaccepted Assets') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('unaccepted-assets.export', ['format' => 'pdf']) }}">
                            {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('unaccepted-assets.export', ['format' => 'csv']) }}">
                            {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('unaccepted-assets.export', ['format' => 'xlsx']) }}">
                            {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('unaccepted-assets.export', ['format' => 'print']) }}" target="_blank">
                            {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('unaccepted_assets.table')
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

        let unacceptedAssetTable = $('#unacceptedAssetTable').DataTable({
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
                url: "{{ route('unaccepted-assets.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                { data: 'title', name: 'title' },
                { data: 'asset', name: 'asset' },
                {
                    data: 'image',
                    name: 'image',
                    render: function(data) {
                        return data ? `<img src="${data}" width="50" height="50">` : '';
                    }
                },
                { data: 'serial_number', name: 'serial_number' },
                { data: 'checkout_for', name: 'checkout_for' },
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div style="float: right;">
                                <a title="Delete" href="#"
                                   class="btn btn-danger action-btn has-icon delete-btn"
                                   style="float:right;margin:2px;"
                                   data-id="${data}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        `;
                    }
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let assetId = $(event.currentTarget).data('id');
            deleteItem(
                "{{ route('unaccepted-assets.destroy', ['unaccepted_asset' => ':id']) }}".replace(':id', assetId),
                '#unacceptedAssetTable',
                "{{ __('Unaccepted Asset') }}"
            );
        });

        function deleteItem(url, tableId, header) {
            if (!confirm(__('Are you sure you want to delete this ' + header + '?'))) {
                return false;
            }

            $.ajax({
                url: url,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(result) {
                    if (result.success) {
                        $(tableId).DataTable().ajax.reload(null, false);
                        displaySuccessMessage(result.message);
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                }
            });
        }
    </script>
@endsection
