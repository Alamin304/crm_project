@extends('layouts.app')
@section('title')
    {{ __('messages.products.unit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.products.unit') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            @can('create_product_units')
                <div class="float-right">
                    <a href="{{ route('products.unit.create') }}"
                        class="btn btn-primary form-btn">{{ __('messages.products.add_unit') }} </a>
                </div>
            @endcan
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @if (session()->has('flash_notification'))
                        @foreach (session('flash_notification') as $message)
                            <div class="alert alert-{{ $message['level'] }}">
                                {{ $message['message'] }}
                            </div>
                        @endforeach
                    @endif
                    @include('product_unit.table_unit')
                </div>
            </div>
        </div>
    </section>
    @include('products.templates.templates')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        'use strict';
        let unitCreateUrl = route('products.unit.store');
        let unitUrl = route('products.unit.index') + '/';
        $(document).on('submit', '#addNewForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewForm', '#btnSave', 'loading');


            let description = $('<div />').
            html($('#createDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#createDescription').summernote('isEmpty')) {
                $('#createDescription').val('');
            } else if (empty) {
                displayErrorMessage(
                    'Description field is not contain only white space');
                processingBtn('#addNewForm', '#btnSave', 'reset');
                return false;
            }
            $.ajax({
                url: unitCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        $('#addModal').modal('hide');
                        $('#productsUnitTable').DataTable().ajax.reload(null, false);
                        $('#productUnit_title').val('');
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
        let tbl = $('#productsUnitTable').DataTable({
            oLanguage: {
                'sEmptyTable': Lang.get('messages.common.no_data_available_in_table'),
                'sInfo': Lang.get('messages.common.data_base_entries'),
                sLengthMenu: Lang.get('messages.common.menu_entry'),
                sInfoEmpty: Lang.get('messages.common.no_entry'),
                sInfoFiltered: Lang.get('messages.common.filter_by'),
                sZeroRecords: Lang.get('messages.common.no_matching'),
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: route('products.unit.index'),
            },
            columns: [{
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row.title;
                        return element.value;
                    },
                    name: 'title',
                    width: '40%'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        element.innerHTML = row
                            .description; // Assuming your data source has a 'description' field
                        return element.value;
                    },
                    name: 'description',
                    width: '40%'
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '200px'
                }
            ],
            responsive: true // Enable responsive features
        });


        $(document).on('click', '.edit-btn', function(event) {
            let UnitId = $(event.currentTarget).data('id');
            const url = route('products.unit.edit', UnitId);
            window.location.href = url;
        });
        $(document).on('click', '.delete-btn', function(event) {
            let productId = $(event.currentTarget).data('id');
            deleteItem(route('products.unit.destroy', productId), '#productsUnitTable',
                Lang.get('messages.products.unit'));
        });
    </script>
    <script>
        // Define messages for translations
        var messages = {
            delete: "{{ __('messages.common.delete') }}",
            edit: "{{ __('messages.common.edit') }}",
            view: "{{ __('messages.common.view') }}"
        };
        // Define permissions
        var permissions = {
            updateItem: "{{ auth()->user()->can('update_product_units') ? 'true' : 'false' }}",
            deleteItem: "{{ auth()->user()->can('delete_product_units') ? 'true' : 'false' }}",
            viewItem: "{{ auth()->user()->can('view_product_units') ? 'true' : 'false' }}"
        };
        // Function to render action buttons based on permissions
        function renderActionButtons(id) {
            let buttons = '';
            if (permissions.updateItem === 'true') {
                let editUrl = `{{ route('products.unit.edit', ':id') }}`;
                editUrl = editUrl.replace(':id', id);
                buttons += `
                <a title="${messages.edit}" href="${editUrl}" class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
            `;
            }
            if (permissions.viewItem === 'true') {
                let viewUrl = `{{ route('products.unit.view', ':id') }}`;
                viewUrl = viewUrl.replace(':id', id);
                buttons += `
                <a title="${messages.view}" href="${viewUrl}" class="btn btn-info action-btn has-icon view-btn" style="float:right;margin:2px;">
                    <i class="fa fa-eye"></i>
                </a>
            `;
            }

            if (permissions.deleteItem === 'true') {
                buttons += `
                <a title="${messages.delete}" href="#" class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="float:right;margin:2px;">
                    <i class="fa fa-trash"></i>
                </a>
            `;
            }
            return buttons;
        }
    </script>
@endsection
