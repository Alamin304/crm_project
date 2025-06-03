@extends('layouts.app')
@section('title')
    {{ __('messages.loyalty_users.loyalty_users') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<section class="section">
    <div class="section-header item-align-right">
        <h1>{{ __('messages.loyalty_users.loyalty_users') }}</h1>
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
                    <a class="dropdown-item" href="{{ route('loyalty-users.export', ['format' => 'pdf']) }}">
                        {{ __('PDF') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('loyalty-users.export', ['format' => 'csv']) }}">
                        {{ __('CSV') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('loyalty-users.export', ['format' => 'xlsx']) }}">
                        {{ __('Excel') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('loyalty-users.export', ['format' => 'print']) }}" target="_blank">
                        {{ __('Print') }}
                    </a>
                </div>
            </div>

            {{-- <div>
                <a href="{{ route('loyalty-users.create') }}" class="btn btn-primary btn-sm form-btn">
                    {{ __('messages.loyalty_users.add') }}
                </a>
            </div> --}}
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <table class="table table-responsive-sm table-striped table-bordered" id="loyaltyUserTable">
                    <thead>
                        <tr>
                            <th>{{ __('messages.loyalty_users.customer') }}</th>
                            <th>{{ __('messages.loyalty_users.email') }}</th>
                            <th>{{ __('messages.loyalty_users.membership') }}</th>
                            <th>{{ __('messages.loyalty_users.loyalty_point') }}</th>
                            <th>{{ __('messages.common.action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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

    let loyaltyUserTable = $('#loyaltyUserTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('loyalty-user-lists.index') }}",
        },
        oLanguage: {
            'sEmptyTable': "{{ __('messages.common.no_data_available_in_table') }}",
            'sInfo': "{{ __('messages.common.data_base_entries') }}",
            sLengthMenu: "{{ __('messages.common.menu_entry') }}",
            sInfoEmpty: "{{ __('messages.common.no_entry') }}",
            sInfoFiltered: "{{ __('messages.common.filter_by') }}",
            sZeroRecords: "{{ __('messages.common.no_matching') }}",
        },
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        columns: [
            { data: 'customer', name: 'customer' },
            { data: 'email', name: 'email' },
            { data: 'membership', name: 'membership' },
            { data: 'loyalty_point', name: 'loyalty_point' },
            {
                data: function(row) {
                    return renderActionButtons(row.id);
                },
                name: 'id',
                orderable: false,
                searchable: false,
                width: '150px'
            }
        ],
        responsive: true
    });

    function renderActionButtons(id) {
        let viewUrl = "{{ route('loyalty-user-lists.show', ':id') }}".replace(':id', id);


        return `
            <a href="${viewUrl}" title="View" class="btn btn-info btn-sm mr-1"><i class="fas fa-eye"></i></a>

        `;
    }

</script>
@endsection
