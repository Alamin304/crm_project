@extends('layouts.app')

@section('title')
    {{ __('messages.campaigns.campaigns') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.campaigns.campaigns') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.campaigns.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('campaigns.export', ['format' => 'pdf']) }}">
                            {{ __('messages.common.pdf') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('campaigns.export', ['format' => 'csv']) }}">
                            {{ __('messages.common.csv') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('campaigns.export', ['format' => 'xlsx']) }}">
                            {{ __('messages.common.excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('campaigns.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('messages.common.print') }}
                        </a>
                    </div>
                </div>
                <div class="float-right">
                    <a href="{{ route('campaigns.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.campaigns.add') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('campaigns.table')
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
        let campaignCreateUrl = "{{ route('campaigns.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: campaignCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#campaignTable').DataTable().ajax.reload(null, false);
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

        let campaignTable = $('#campaignTable').DataTable({
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
                url: "{{ route('campaigns.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'campaign_name',
                    name: 'campaign_name',
                    width: '12%'
                },
                {
                    data: 'company',
                    name: 'company',
                    width: '10%'
                },
                {
                    data: 'position',
                    name: 'position',
                    width: '10%'
                },
                {
                    data: 'working_form',
                    name: 'working_form',
                    width: '8%'
                },
                {
                    data: 'department',
                    name: 'department',
                    width: '10%'
                },
                {
                    data: 'recruitment_plan',
                    name: 'recruitment_plan',
                    width: '12%',
                    orderable: false
                },
                {
                    data: 'recruited_quantity',
                    name: 'recruited_quantity',
                    width: '8%'
                },
                {
                    data: 'recruitment_channel_from',
                    name: 'recruitment_channel_from',
                    width: '10%'
                },
                {
                    data: 'managers',
                    name: 'managers',
                    width: '10%',
                    orderable: false
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    width: '8%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '150px',
                    orderable: false,
                    className: 'action-column' // Prevent action buttons from wrapping
                }
            ],
            responsive: true,
            scrollX: true, // Enable horizontal scrolling
            autoWidth: false, // Disable automatic width calculation
            order: [
                [0, 'desc']
            ]
        });

        $(document).on('click', '.delete-btn', function(event) {
            let campaignId = $(event.currentTarget).data('id');
            deleteItem("{{ route('campaigns.destroy', ':id') }}".replace(':id', campaignId),
                '#campaignTable', "{{ __('messages.campaigns.campaigns') }}");
        });

        function renderActionButtons(id) {
            let deleteUrl = "{{ route('campaigns.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('campaigns.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('campaigns.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a title="{{ __('messages.common.delete') }}" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                    <a title="{{ __('messages.common.view') }}" href="${viewUrl}"
                       class="btn btn-info action-btn has-icon view-btn"
                       style="margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="{{ __('messages.common.edit') }}" href="${editUrl}"
                       class="btn btn-warning action-btn has-icon edit-btn"
                       style="margin:2px;">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            `;
        }
    </script>
@endsection
