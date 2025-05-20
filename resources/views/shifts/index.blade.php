@extends('layouts.app')
@section('title')
    {{ __('messages.shifts.shifts') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
<style>
        .modal-backdrop {
            display: none !important;
        }

        body.modal-open {
            overflow: auto !important;
            padding-right: 0 !important;
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-dialog {
            margin-top: 10vh;
            z-index: 2050 !important;
        }

        .modal-content {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        .modal input,
        .modal button,
        .modal a {
            position: relative;
            z-index: 2060 !important;
        }
        /* Action button styles */
.action-btn {
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
    line-height: 32px !important;
    text-align: center !important;
    border-radius: 4px !important;
    margin: 2px !important;
    float: right !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.action-btn i {
    font-size: 14px !important;
    line-height: 1 !important;
    margin: 0 !important;
}

/* Specific button colors */
.btn-warning.action-btn {
    background-color: #f0ad4e !important;
    border-color: #eea236 !important;
}

.btn-info.action-btn {
    background-color: #5bc0de !important;
    border-color: #46b8da !important;
}

.btn-danger.action-btn {
    background-color: #d9534f !important;
    border-color: #d43f3a !important;
}

/* Button hover effects */
.action-btn:hover {
    opacity: 0.85 !important;
}
    </style>
@section('content')
    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors (for row-level import validation failures) --}}
    @if (session()->has('failures'))
        <div class="alert alert-danger">
            <strong>Import failed due to the following row errors:</strong>
            <ul>
                @foreach (session()->get('failures') as $failure)
                    <li>
                        Row {{ $failure->row() }}:
                        @foreach ($failure->errors() as $error)
                            {{ $error }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.shifts.name') }} {{ __('messages.shifts.list') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right d-flex">
                <div class="dropdown export-dropdown mr-2">
                    <button class="btn btn-primary dropdown-toggle form-btn" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('messages.shifts.export') }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="{{ route('shifts.export', ['format' => 'pdf']) }}">
                            <i class="fas fa-file-pdf text-danger mr-2"></i> {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('shifts.export', ['format' => 'csv']) }}">
                            <i class="fas fa-file-csv text-success mr-2"></i> {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('shifts.export', ['format' => 'xlsx']) }}">
                            <i class="fas fa-file-excel text-primary mr-2"></i> {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('shifts.export', ['format' => 'print']) }}"
                            target="_blank">
                            <i class="fas fa-print text-info mr-2"></i> {{ __('Print') }}
                        </a>
                        <div class="dropdown-divider"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm form-btn mr-2" id="shiftImportButton">
    <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
</button>
                <div class="float-right">
                    <a href="{{ route('shifts.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.shifts.add') }}
                    </a>
                </div>
            </div>
            {{-- @can('create_shifts')
                <div class="float-right">
                    <a href="{{ route('shifts.create') }}" class="btn btn-primary form-btn">
                        {{ __('messages.shifts.add') }} </a>
                </div>
            @endcan --}}
        </div>
        <!-- Shift Import Modal -->
<div class="modal fade" id="shiftImportModal" tabindex="-1" role="dialog"
    aria-labelledby="shiftImportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('shifts.import') }}" method="POST" enctype="multipart/form-data"
            id="shiftImportForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shiftImportModalLabel">{{ __('Import Shifts via CSV') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <a href="{{ route('shifts.sample-csv') }}" class="btn btn-info btn-sm mb-3">
                        <i class="fas fa-download mr-1"></i> {{ __('Download Sample CSV') }}
                    </a>

                    <div class="form-group">
                        <label for="shiftCsvFile">{{ __('Upload CSV File') }}</label>
                        <input type="file" name="file" class="form-control-file" id="shiftCsvFile"
                            required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-import mr-1"></i> {{ __('Import') }}
                    </button>
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
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
                    @include('shifts.table')
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
        'use strict';

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
                url: route('shifts.index'),
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '10%',
                    orderable: false,
                    searchable: false
                }, {
                    data: function(row) {
                        let element = document.createElement('textarea');
                        return row.name;
                    },
                    name: 'name',
                    // width: '10%'
                }, {
                    data: function(row) {
                        // Convert the shift_start_time to 12-hour format
                        return formatTimeTo12Hour(row.shift_start_time);
                    },
                    name: 'shift_start_time',
                    // width: '10%'
                },
                {
                    data: function(row) {
                        // Convert the shift_end_time to 12-hour format
                        return formatTimeTo12Hour(row.shift_end_time);
                    },
                    name: 'shift_end_time',
                    // width: '10%'
                },
                { // Add this new column for duration
                    data: function(row) {
                        return calculateShiftDuration(row.shift_start_time, row.shift_end_time);
                    },
                    name: 'duration',
                    orderable: false,
                    searchable: false,
                    // Optional: Add a title attribute that shows the full calculation on hover
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return data;
                        }
                        return data;
                    }
                },
                // {
                //     data: function(row) {
                //         // Convert the lunch_start_time to 12-hour format
                //         return row.lunch_start_time ? formatTimeTo12Hour(row.lunch_start_time) : '';
                //     },
                //     name: 'lunch_start_time',
                //     width: '12%'
                // },
                // {
                //     data: function(row) {
                //         // Convert the lunch_end_time to 12-hour format
                //         return row.lunch_end_time ? formatTimeTo12Hour(row.lunch_end_time) : '';
                //     },
                //     name: 'lunch_end_time',
                //     width: '10%'
                // },
                // {
                //     data: function(row) {
                //         return row.color; // Return color data
                //     },
                //     name: 'color',
                //     width: '10%',
                //     render: function(data, type, row) {
                //         // Return a styled div with the background color set to the value of `data`
                //         return '<div style="width: 30px; height: 30px; border-radius: 10%; background-color: ' +
                //             data + ';"></div>';
                //     }
                // },
                // {

                //     data: function(row) {
                //         let element = document.createElement('textarea');
                //         element.innerHTML = row
                //             .description; // Assuming your data source has a 'description' field
                //         return element.value;
                //     },
                //     name: 'description',
                //     width: '20%'
                // },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                }
            ],
            responsive: true // Enable responsive features
        });

        $(document).on('click', '.edit-btn', function(event) {
            let did = $(event.currentTarget).data('id');
            const url = route('shifts.edit', did);
            window.location.href = url;
        });
        $(document).on('click', '.delete-btn', function(event) {
            let assetCateogryId = $(event.currentTarget).data('id');
            deleteItem(route('shifts.destroy', assetCateogryId), '#designationTable',
                '{{ __('messages.shifts.name') }}');
        });

        function formatTimeTo12Hour(time24) {
            if (!time24) return ''; // Handle null or empty values

            let [hours, minutes] = time24.split(':').map(Number);
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours || 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;

            return `${hours}:${minutes} ${ampm}`;
        }

        function calculateShiftDuration(startTime, endTime) {
            if (!startTime || !endTime) return '';

            // Parse the times
            const start = new Date(`2000-01-01T${startTime}`);
            const end = new Date(`2000-01-01T${endTime}`);

            // Handle overnight shifts (end time is next day)
            if (end < start) {
                end.setDate(end.getDate() + 1);
            }

            // Calculate difference in hours
            const diffMs = end - start;
            const diffHours = Math.round((diffMs / (1000 * 60 * 60)) * 10) / 10; // Round to 1 decimal

            return `${diffHours} ${diffHours === 1 ? 'hour' : 'hours'}`;
        }
    </script>

    <script>
        // Define messages for translations
        var messages = {
            delete: "{{ __('messages.common.delete') }}",
            edit: "{{ __('messages.common.edit') }}",
            view: "{{ __('messages.common.view') }}"
        };

        // Define permissions
        // var permissions = {
        //     updateItem: "{{ auth()->user()->can('update_shifts') ? 'true' : 'false' }}",
        //     deleteItem: "{{ auth()->user()->can('delete_shifts') ? 'true' : 'false' }}",
        //     viewItem: "{{ auth()->user()->can('view_shifts') ? 'true' : 'false' }}"
        // };

        // Function to render action buttons based on permissions
        function renderActionButtons(id) {
            let buttons = '';



            // if (permissions.updateItem === 'true') {
            let editUrl = `{{ route('shifts.edit', ':id') }}`;
            editUrl = editUrl.replace(':id', id);
            buttons += `
                <a title="${messages.edit}" href="${editUrl}" class="btn btn-warning action-btn has-icon edit-btn" style="float:right;margin:2px;">
                    <i class="fa fa-edit"></i>
                </a>
            `;
            // }
            // if (permissions.viewItem === 'true') {
            let viewUrl = `{{ route('shifts.view', ':id') }}`;
            viewUrl = viewUrl.replace(':id', id);
            buttons += `
                <a title="${messages.view}" href="${viewUrl}" class="btn btn-info action-btn has-icon view-btn" style="float:right;margin:2px;">
                    <i class="fa fa-eye"></i>
                </a>
            `;
            // }

            // if (permissions.deleteItem === 'true') {
            buttons += `
                <a title="${messages.delete}" href="#" class="btn btn-danger action-btn has-icon delete-btn" data-id="${id}" style="float:right;margin:2px;">
                    <i class="fa fa-trash"></i>
                </a>
            `;
            // }

            return buttons;
        }
    </script>
    <script>
    $(document).ready(function() {
        $('#shiftImportModal').modal('hide');
        $('.modal').removeClass('show');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();

        $('#shiftImportModal').css({
            'display': 'none',
            'padding-right': '0px'
        });

        $('#shiftImportButton').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#shiftImportModal').modal('show');
            window.manuallyOpenedShift = true;
        });

        $('#shiftImportModal').on('shown.bs.modal', function() {
            $('#shiftCsvFile').focus();
        });

        $('#shiftImportModal').on('hidden.bs.modal', function() {
            $('#shiftImportForm')[0].reset();
            window.manuallyOpenedShift = false;
        });

        setTimeout(function() {
            if ($('#shiftImportModal').hasClass('show') && !window.manuallyOpenedShift) {
                $('#shiftImportModal').modal('hide');
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
            }
        }, 100);

        $(document).on('click', function(e) {
            if ($(e.target).hasClass('modal') && !$(e.target).hasClass('modal-dialog')) {
                $('#shiftImportModal').modal('hide');
            }
        });
    });
</script>
@endsection
