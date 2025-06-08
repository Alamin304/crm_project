@extends('layouts.app')
@section('title')
    {{ __('messages.audit.audits') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.audit.audits') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right d-flex">
                <a href="{{ route('audits.create') }}" class="btn btn-primary form-btn">
                    {{ __('messages.audit.add') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <table class="table table-responsive-sm table-responsive-md table-responsive-lg table-striped table-bordered" id="auditTable">
                        <thead>
                            <tr>
                                <th>{{ __('messages.audit.title') }}</th>
                                <th>{{ __('messages.audit.auditor') }}</th>
                                <th>{{ __('messages.audit.audit_date') }}</th>
                                <th>{{ __('messages.audit.status') }}</th>
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

        let auditTable = $('#auditTable').DataTable({
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
                url: "{{ route('audits.index') }}"
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [
                {
                    data: 'title',
                    name: 'title',
                    width: '25%'
                },
                {
                    data: 'auditor',
                    name: 'auditor',
                    width: '25%'
                },
                {
                    data: 'audit_date',
                    name: 'audit_date',
                    width: '20%'
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '20%',
                    render: function(data) {
                        let statusClass = '';
                        if (data === 'approved') {
                            statusClass = 'badge-success';
                        } else if (data === 'rejected') {
                            statusClass = 'badge-danger';
                        } else {
                            statusClass = 'badge-info';
                        }
                        return `<span class="badge ${statusClass}">${data}</span>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    width: '10%',
                    orderable: false
                }
            ],
            responsive: true
        });

        $(document).on('click', '.delete-btn', function(event) {
            let auditId = $(event.currentTarget).data('id');
            deleteItem("{{ route('audits.destroy', ':id') }}".replace(':id', auditId), '#auditTable',
                "{{ __('messages.audit.audit') }}");
        });
    </script>
@endsection
