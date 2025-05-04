@extends('layouts.app')
@section('title')
    {{ __('messages.beds.beds') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.beds.beds') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>

            <div class="float-right d-flex">
                <a href="{{ route('beds.export', ['format' => 'xlsx']) }}" class="btn btn-success mr-2">
                    <i class="fas fa-file-excel"></i> {{ __('Export Excel') }}
                </a>
                <a href="{{ route('beds.export', ['format' => 'csv']) }}" class="btn btn-info mr-2">
                    <i class="fas fa-file-csv"></i> {{ __('Export CSV') }}
                </a>
                <a href="{{ route('beds.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('messages.beds.add') }}
                </a>
            </div>

            {{-- <div class="float-right">
                <a href="{{ route('beds.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.beds.add') }} <i class="fas fa-plus"></i>
                </a>
            </div> --}}
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('beds.table')
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

        let bedCreateUrl = "{{ route('beds.store') }}";
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');

            $.ajax({
                url: bedCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#bedTable').DataTable().ajax.reload(null, false);
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

        let bedTable = $('#bedTable').DataTable({
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
                url: "{{ route('beds.index') }}",
            },
            columns: [{
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.name;
                        return element.value;
                    },
                    name: 'name',
                    width: '30%'
                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row
                            .description; // Assuming your data source has a 'description' field
                        return element.value;
                    },
                    name: 'description',
                    width: '20%'
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
            let bedId = $(event.currentTarget).data('id');
            deleteItem("{{ route('beds.destroy', ['bed' => ':id']) }}".replace(':id', bedId),
                '#bedTable', "{{ __('messages.beds.beds') }}");
        });

        // Action buttons rendering
        function renderActionButtons(id) {
            let editUrl = "{{ route('beds.edit', ':id') }}".replace(':id', id);
            let deleteUrl = "{{ route('beds.destroy', ':id') }}".replace(':id', id);

            let buttons = `
        <a title="Edit" href="${editUrl}"
           class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
            <i class="fas fa-edit"></i>
        </a>
        <a title="Delete" href="#"
           class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="float:right;margin:2px;">
            <i class="fas fa-trash"></i>
        </a>
    `;

            return buttons;
        }
    </script>
@endsection
