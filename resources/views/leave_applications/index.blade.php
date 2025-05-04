@extends('layouts.app')
@section('title')
    {{ __('messages.leave-applications.leave-applications') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.leave-applications.leave-applications') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="mr-3">
                    <label for="statusDropdown">Branches</label>
                    {{ Form::select('branches', $usersBranches ?? [], null, ['id' => 'filterBranch', 'class' => 'form-control select2', 'placeholder' => __('messages.placeholder.branches')]) }}
                </div>
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            @can('create_leave_applications')
                <div class="float-right">
                    <a href="{{ route('leave-applications.create') }}" class="btn btn-primary "
                        style="line-height:30px;">{{ __('messages.leave-applications.add') }} </a>
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
                    @include('leave_applications.table')
                </div>
            </div>
        </div>
    </section>
    @include('leave_applications.templates.templates')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <!-- DataTables and Buttons Extension JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

    <!-- pdfmake for PDF export -->
    <script src="{{ asset('assets/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/pdfmake.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        let tbl = $('#assetTable').DataTable({
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
                url: route('leave-applications.index'),
                data: function(d) {
                    d.filterBranch = $("#filterBranch").val();
                },
                beforeSend: function() {
                    startLoader();
                },
                complete: function() {
                    stopLoader();
                }
            },

            order: [
                [0, 'desc'] // Ordering by the hidden 'created_at' column (index 2) in descending order
            ],
            columnDefs: [{
                targets: 0, // Adjust the index for the hidden 'created_at' column
                orderable: true,
                visible: false, // Hide the 'created_at' column
            }],
            columns: [{
                    data: 'updated_at', // Ensure this matches the data key for created_at in your data source
                    name: 'updated_at'
                }, {
                    data: function(row) {

                        let element = document.createElement('textarea');
                        return row.branch?.name ?? "";
                    },
                    name: 'branch.name',
                    width: '12%',
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.employee) {
                            return row.employee.iqama_no;
                        }
                        return '';
                    },
                    name: 'iqama_no',
                    width: '8%',
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.employee) {
                            return row.employee.name;
                        }
                        return '';
                    },
                    name: 'employee_id',
                    width: '15%',
                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        if (row.leave) {
                            return row.leave.name;
                        }
                        return '';
                    },
                    name: 'leave_id',
                    width: '10%',
                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.from_date ?? '';
                    },
                    name: 'from_date',
                    width: '10%',
                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.end_date ?? '';
                    },
                    name: 'end_date',
                    width: '10%',
                },
                {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.total_days ?? '';
                    },
                    name: 'total_days',
                    width: '9%',
                },

                {
                    data: function(row) {
                        // Check if hard_copy is available and construct the URL
                        if (row.hard_copy) {
                            // Construct the URL for the file
                            const fileUrl =
                                `/uploads/public/leave_applications/${encodeURIComponent(row.hard_copy)}`;
                            // Return the anchor tag with the eye icon
                            return `<a  href="${fileUrl}" target="_blank" title="View File"><i class="fas fa-eye"></i></a>`;
                        } else {
                            return 'No file available';
                        }
                    },
                    name: 'hard_copy',
                    orderable: false,
                    width: '8%',
                },

                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    width: '10%'
                }
            ],
            responsive: true,
            dom: "Bfrtip", // Ensure Buttons (B) is part of the DOM structure
            buttons: [

                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                    className: 'btn btn-sm',
                    title: 'Leave-applications',
                    exportOptions: {
                        // Exclude the action column from the export
                        columns: function(idx, data, node) {
                            return idx !== 0 && idx !== 9 && idx !== 8; // Exclude the action column at index 4
                        }
                    }
                }, {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    className: 'btn btn-sm', // Styled button
                    orientation: 'portrait',
                    title: 'Leave-applications',
                    pageSize: 'A4',
                    exportOptions: {
                        // Exclude the action column from the export
                        columns: function(idx, data, node) {
                            return idx !== 0 && idx !== 9 && idx !== 8; // Exclude the action column at index 3
                        }
                    },
                    customize: function(doc) {
                        // Optional customization of the PDF
                        doc.content[1].table.widths =
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                        // Right-align the last column
                        var lastColumnIndex = doc.content[1].table.body[0].length -
                            1; // Get index of the last column
                        doc.content[1].table.body.forEach(function(row, rowIndex) {
                            if (rowIndex > 0) { // Skip the header row
                                row[lastColumnIndex].alignment = 'right';
                            }
                        });
                    }
                }

            ],
            pageLength: 10
        });


        $('#filterBranch').change(function() {
            tbl.ajax.reload(); // Reload DataTable with new status filter
        });

        $(document).on('click', '.edit-btn', function(event) {
            let assetId = $(event.currentTarget).data('id');
            const url = route('leave-applications.edit', assetId);
            window.location.href = url;
        });
        $(document).on('click', '.delete-btn', function(event) {
            let assetCateogryId = $(event.currentTarget).data('id');
            deleteItem(route('leave-applications.destroy', assetCateogryId), '#assetTable',
                "{{ __('messages.leave-applications.name') }}");
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
            updateItem: "{{ auth()->user()->can('update_leave_applications') ? 'true' : 'false' }}",
            deleteItem: "{{ auth()->user()->can('delete_leave_applications') ? 'true' : 'false' }}",
            viewItem: "{{ auth()->user()->can('view_leave_applications') ? 'true' : 'false' }}"
        };

        // Function to render action buttons based on permissions
        function renderActionButtons(id) {
            let buttons = '';



            if (permissions.updateItem === 'true') {
                let editUrl = `{{ route('leave-applications.edit', ':id') }}`;
                editUrl = editUrl.replace(':id', id);
                buttons += `
                <a title="${messages.edit}" href="${editUrl}" class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
            `;
            }
            if (permissions.viewItem === 'true') {
                let viewUrl = `{{ route('leave-applications.view', ':id') }}`;
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
