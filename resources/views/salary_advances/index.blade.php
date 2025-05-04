@extends('layouts.app')
@section('title')
    {{ __('messages.salary_advances.name') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.salary_advances.name') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            @can('create_loan')
                <div class="float-right">
                    <a href="{{ route('salary_advances.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.salary_advances.add') }} </a>
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
                    @include('salary_advances.table')
                </div>
            </div>
        </div>
    </section>

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
        let tbl = $('#designationTable').DataTable({
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
                url: route('salary_advances.index'),
            },

            columns: [{
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.employee && row.employee.iqama_no) {
                            return row.employee.iqama_no;
                        }
                        return '';
                    },
                    name: 'iqama_no',
                    width: '9%'

                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.employee && row.employee.name) {
                            return row.employee.name;
                        }
                        return '';
                    },
                    name: 'employee_id',


                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.employee.branch?.name ?? '';
                    },
                    name: 'employee.branch.name',

                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');

                        if (row.permitted_by.name && row.permitted_by.name) {
                            return row.permitted_by.name;
                        }
                        return '';
                    },
                    name: 'permitted_by',

                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.amount ? row.amount.toFixed(2) : '0.00';
                    },
                    name: 'amount',

                    className: 'text-right'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');

                        return row.repayment_amount ? row.repayment_amount.toFixed(2) : '0.00';
                    },
                    name: 'repayment_amount',

                    className: 'text-right'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.interest_percentage + '%';
                    },
                    name: 'interest_percentage',

                    className: 'text-right'
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');

                        return row.installment ? row.installment.toFixed(2) : '0.00';

                    },
                    name: 'installment',

                    className: 'text-right'
                },
                {
                    data: function(row) {
                        let date = new Date(row.approved_date);
                        let day = String(date.getDate()).padStart(2, '0');
                        let month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
                        let year = date.getFullYear();
                        // Format the date as d-m-Y
                        let formattedDate = `${day}-${month}-${year}`;
                        return formattedDate;
                    },
                    name: 'approved_date',

                },

                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.status;
                    },
                    name: 'status',

                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '8%'
                }
            ],
            scrollX: true, // Enables horizontal scrolling
            scrollCollapse: true,
            responsive: false, // Disable responsive behavior to prevent column stacking
            autoWidth: false,
        });

        $(document).on('click', '.edit-btn', function(event) {
            let did = $(event.currentTarget).data('id');
            const url = route('salary_advances.edit', did);
            window.location.href = url;
        });

        $(document).on('click', '.delete-btn', function(event) {
            let assetCateogryId = $(event.currentTarget).data('id');
            deleteItem(route('salary_advances.destroy', assetCateogryId), '#designationTable',
                '{{ __('messages.salary_advances.name') }}');
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
            updateItem: "{{ auth()->user()->can('update_salary_advances') ? 'true' : 'false' }}",
            deleteItem: "{{ auth()->user()->can('delete_salary_advances') ? 'true' : 'false' }}",
            viewItem: "{{ auth()->user()->can('view_salary_advances') ? 'true' : 'false' }}"
        };

        // Function to render action buttons based on permissions
        function renderActionButtons(id) {
            let buttons = '';



            if (permissions.updateItem === 'true') {
                let editUrl = `{{ route('salary_advances.edit', ':id') }}`;
                editUrl = editUrl.replace(':id', id);
                buttons += `
                <a title="${messages.edit}" href="${editUrl}" class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
            `;
            }
            if (permissions.viewItem === 'true') {
                let viewUrl = `{{ route('salary_advances.view', ':id') }}`;
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
