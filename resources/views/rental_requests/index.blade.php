@extends('layouts.app')

@section('title')
    {{ __('Rental Requests') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .export-dropdown {
            min-width: 120px;
        }

        .export-dropdown .dropdown-menu {
            min-width: 160px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-submitted {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-sent {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-waiting {
            background-color: #ffeeba;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-declined {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-complete {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-expired {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-cancelled {
            background-color: #f5f5f5;
            color: #6c757d;
        }

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

        .inspected-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .inspected-yes {
            background-color: #d4edda;
            color: #155724;
        }

        .inspected-no {
            background-color: #f8d7da;
            color: #721c24;
        }

    </style>
@endsection

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

    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('Rental Requests') }}</h1>
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
                        <a class="dropdown-item" href="{{ route('rental_requests.export', ['format' => 'pdf']) }}">
                            {{ __('PDF') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('rental_requests.export', ['format' => 'csv']) }}">
                            {{ __('CSV') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('rental_requests.export', ['format' => 'xlsx']) }}">
                            {{ __('Excel') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('rental_requests.export', ['format' => 'print']) }}"
                            target="_blank">
                            {{ __('Print') }}
                        </a>
                    </div>
                </div>
                <div class="float-right">
                    <a href="{{ route('rental_requests.create') }}" class="btn btn-primary form-btn">
                        {{ __('Add') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('rental_requests.table')
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

        let rentalRequestTable = $('#rentalRequestTable').DataTable({
            oLanguage: {
                'sEmptyTable': "{{ __('No data available in table') }}",
                'sInfo': "{{ __('Showing _START_ to _END_ of _TOTAL_ entries') }}",
                sLengthMenu: "{{ __('Show _MENU_ entries') }}",
                sInfoEmpty: "{{ __('Showing 0 to 0 of 0 entries') }}",
                sInfoFiltered: "{{ __('(filtered from _MAX_ total entries)') }}",
                sZeroRecords: "{{ __('No matching records found') }}",
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('rental_requests.index') }}",
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            columns: [{
                    data: 'request_number',
                    name: 'request_number',
                    width: '10%'
                },
                {
                    data: 'property_name',
                    name: 'property_name',
                    width: '15%'
                },
                {
                    data: 'customer',
                    name: 'customer',
                    width: '15%',
                    render: function(data) {
                        return data ? data.name : 'N/A';
                    }
                },
                {
                    data: 'inspected_property',
                    name: 'inspected_property',
                    width: '10%',
                    render: function(data) {
                        let badgeClass = data ? 'inspected-yes' : 'inspected-no';
                        let text = data ? 'Yes' : 'No';
                        return `<span class="inspected-badge ${badgeClass}">${text}</span>`;
                    }
                },
                {
                    data: 'contract_amount',
                    name: 'contract_amount',
                    width: '10%',
                    render: function(data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : 'N/A';
                    }
                },
                {
                    data: 'property_price',
                    name: 'property_price',
                    width: '10%',
                    render: function(data) {
                        return data ? '$' + parseFloat(data).toFixed(2) : 'N/A';
                    }
                },
                {
                    data: 'term',
                    name: 'term',
                    width: '8%',
                    render: function(data) {
                        return data ? data + ' months' : 'N/A';
                    }
                },
                {
                    data: 'start_date',
                    name: 'start_date',
                    width: '10%',
                    render: function(data) {
                        return data ? moment(data).format('YYYY-MM-DD') : 'N/A';
                    }
                },
                {
                    data: 'end_date',
                    name: 'end_date',
                    width: '10%',
                    render: function(data) {
                        return data ? moment(data).format('YYYY-MM-DD') : 'N/A';
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    width: '20%',
                    render: function(data, type, row) {
                        let options = [
                            'submitted',
                            'sent',
                            'waiting for approval',
                            'approved',
                            'declined',
                            'complete',
                            'expired',
                            'cancelled'
                        ];
                        let html = `<select class="form-control status-select" data-id="${row.id}">`;

                        options.forEach(function(option) {
                            let selected = data.toLowerCase() === option ? 'selected' : '';
                            html +=
                                `<option value="${option}" ${selected}>${option.charAt(0).toUpperCase() + option.slice(1)}</option>`;
                        });

                        html += '</select>';
                        return html;
                    }
                },
                {
                    data: function(row) {
                        return renderActionButtons(row.id);
                    },
                    name: 'id',
                    responsivePriority: 1,
                    orderable: false
                }
            ],
            order: [
                [1, 'desc']
            ],
        responsive: true,
        });

        $(document).on('click', '.delete-btn', function(event) {
            let id = $(event.currentTarget).data('id');
            deleteItem("{{ route('rental_requests.destroy', ['rentalRequest' => ':id']) }}".replace(':id', id),
                '#rentalRequestTable', "{{ __('Rental Request') }}");
        });

        $(document).on('change', '.status-select', function() {
            let id = $(this).data('id');
            let status = $(this).val();

            $.ajax({
                url: "{{ route('rental_requests.update-status', ['rentalRequest' => ':id']) }}".replace(
                    ':id', id),
                type: 'PUT',
                data: {
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        displaySuccessMessage(response.message);
                        $('#rentalRequestTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(response) {
                    displayErrorMessage(response.responseJSON.message);
                }
            });
        });


        function renderActionButtons(id) {
            let deleteUrl = "{{ route('rental_requests.destroy', ':id') }}".replace(':id', id);
            let viewUrl = "{{ route('rental_requests.view', ':id') }}".replace(':id', id);
            let editUrl = "{{ route('rental_requests.edit', ':id') }}".replace(':id', id);

            return `
                <div style="float: right;">
                    <a title="View" href="${viewUrl}"
                       class="btn btn-info action-btn has-icon view-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a title="Edit" href="${editUrl}"
                       class="btn btn-warning action-btn has-icon edit-btn"
                       style="float:right;margin:2px;">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a title="Delete" href="#"
                       class="btn btn-danger action-btn has-icon delete-btn"
                       data-id="${id}" style="float:right;margin:2px;">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            `;
        }
    </script>
@endsection
