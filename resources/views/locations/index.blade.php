@extends('layouts.app')
@section('title')
    {{ __('messages.location.locations') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.location.locations') }}</h1>
            <div class="section-header-breadcrumb float-right">
            </div>
            <div class="float-right d-flex">
                <a href="{{ route('locations.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.location.add') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <table class="table table-responsive-sm table-responsive-md table-responsive-lg table-striped table-bordered"
                           id="locationTable">
                        <thead>
                        <tr>
                            <th>{{ __('messages.location.image') }}</th>
                            <th>{{ __('messages.location.location_name') }}</th>
                            <th>{{ __('messages.location.parent') }}</th>
                            <th>{{ __('messages.location.manager') }}</th>
                            <th>{{ __('messages.location.city') }}</th>
                            <th>{{ __('messages.location.country') }}</th>
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

        let locationTable = $('#locationTable').DataTable({
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
                url: "{{ route('locations.index') }}"
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
                            `<img src="${window.location.origin}/storage/${data}" class="location-img" alt="Location Image" width="50">` :
                            `<img src="${window.location.origin}/assets/img/default-location.png" class="location-img" alt="Default Image" width="50">`;
                    },
                    orderable: false
                },
                {
                    data: 'location_name',
                    name: 'location_name',
                    width: '20%'
                },
                {
                    data: 'parent',
                    name: 'parent',
                    width: '15%'
                },
                {
                    data: 'manager',
                    name: 'manager',
                    width: '15%'
                },
                {
                    data: 'city',
                    name: 'city',
                    width: '15%'
                },
                {
                    data: 'country',
                    name: 'country',
                    width: '15%'
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
            let locationId = $(event.currentTarget).data('id');
            deleteItem("{{ route('locations.destroy', ':id') }}".replace(':id', locationId), '#locationTable',
                "{{ __('messages.location.location') }}");
        });

        function renderActionButtons(id) {

            let deleteUrl = "{{ route('locations.destroy', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">

                    <a href="#" title="Delete" class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>`;
        }
    </script>
@endsection
