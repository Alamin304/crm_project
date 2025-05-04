@extends('layouts.app')
@section('title')
    {{ __('messages.customer_statements.name') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!-- DataTables and Buttons Extension CSS -->
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.customer_statements.name') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                    @can('export_statement')
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="exportDropdown"
                                style="line-height: 30px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Export
                            </button>
                            <div class="dropdown-menu " aria-labelledby="exportDropdown" style="width: 50px;">
                                <a class="dropdown-item" href="#" id="export-pdf">PDF</a>
                                <a class="dropdown-item" href="#" id="export-csv">CSV</a>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
            <div class="float-right">

            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="form-group col-sm-12 col-md-2">
                            {{ Form::label('from_date', __('messages.customer_statements.from') . ':') }}
                            <input type="date" id="from_date" name="from_date" class="form-control">
                        </div>

                        <div class="form-group col-sm-12 col-md-2">
                            {{ Form::label('to_date', __('messages.customer_statements.to') . ':') }}
                            <input type="date" id="to_date" name="to_date" class="form-control">
                        </div>
                        <div class="form-group col-sm-12  col-md-2">
                            {{ Form::label('employee_id', __('messages.customer.select_customer') . ':') }}
                            {{ Form::select('customer_id', $customers, null, ['class' => 'form-control', 'required', 'id' => 'customer_select']) }}
                        </div>
                        <div class="form-group col-sm-12  col-md-2">
                            {{ Form::label('employee_id', __('messages.customer.select_project') . ':') }}
                            {{ Form::select('customer_id', [], null, ['class' => 'form-control', 'required', 'id' => 'project_select', 'placeholder' => __('messages.customer.all')]) }}
                        </div>
                        <div class="form-group col-sm-12  col-md-2">
                            {{ Form::label('employee_id', __('messages.branches.name') . ':') }}
                            {{ Form::select('branches', $usersBranches ?? [], null, ['id' => 'filterBranch', 'class' => 'form-control select2', 'placeholder' => __('messages.placeholder.branches')]) }}
                        </div>
                        <div class="form-group col-sm-12 col-md-2">
                            {{ Form::label('status', __('messages.customer_statements.status') . ':') }}
                            {{ Form::select(
                                'payment_status',
                                [
                                    '' => 'All', // Placeholder option
                                    '2' => 'Paid', // Static option for Paid
                                    '3' => 'Partially', // Static option for Partially
                                    '1' => 'Unpaid', // Static option for Unpaid
                                ],
                                null,
                                ['class' => 'form-control', 'required', 'id' => 'paymentStatus'],
                            ) }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    @include('customer_statements.table')
                </div>
            </div>
        </div>
    </section>
    {{-- @include('salary_generates.templates.templates') --}}
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <!-- DataTables and Buttons Extension JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>

    <!-- pdfmake for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="{{ asset('assets/js/vfs_fonts.js') }}"></script>
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
                url: route('customer-statement.index'),
                data: function(d) {
                    // Attach the selected month, customer, and project to the AJAX request
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                    d.customer_select = $('#customer_select').val();
                    d.project_select = $('#project_select').val();
                    d.payment_status = $("#paymentStatus").val();
                    d.filterBranch = $("#filterBranch").val();
                }
            },

            footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                // Helper function to convert strings to numbers for calculations
                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ? i : 0;
                };

                // Calculate the totals for each column (Amount, Received, and Balance)
                var totalAmount = api
                    .column(7) // Amount column (6th column, index 5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var totalReceived = api
                    .column(8) // Received column (7th column, index 6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var totalBalance = totalAmount - totalReceived;

                // Update the footer with the calculated totals
                $(api.column(7).footer()).html('' + totalAmount.toFixed(2)).addClass('text-right pr-2');
                $(api.column(8).footer()).html('' + totalReceived.toFixed(2)).addClass('text-right pr-2');
                $(api.column(9).footer()).html(' ' + totalBalance.toFixed(2)).addClass('text-right pr-2');
            },
            columnDefs: [],
            columns: [{
                    data: function(row) {
                        return row.branch ?? '';
                    },
                    name: 'branch.name',
                }, {
                    data: function(row) {
                        return row.invoice_date;
                    },
                    name: 'invoice_date',
                    className: 'text-left',
                },

                {
                    data: function(row) {
                        return row.invoice_number;
                    },
                    name: 'invoice_number',
                },
                {
                    data: function(row) {
                        return row.type ?? '';
                    },

                },
                {
                    data: function(row) {
                        return row.receipt_date;
                    },
                    name: 'invoice_date',

                },
                {
                    data: function(row) {
                        return row.month ?? '';
                    },
                    name: 'invoice_date',

                },
                {
                    data: function(row) {
                        return row.project_name ?? '';
                    },
                    name: 'project?.project_name',

                },
                {
                    data: function(row) {
                        return (row.debit ?? 0).toFixed(2);
                    },
                    className: 'text-right',
                },
                {
                    data: function(row) {
                        return (row.credit ?? 0).toFixed(2);
                    },
                    className: 'text-right',
                },
                {
                    data: function(row) {
                        return (row.balance ?? 0).toFixed(2);
                    },
                    className: 'text-right',
                },


            ],
            responsive: true,

            lengthMenu: [5, 10, 25, 50, 75, 100],
            pageLength: 1000,

        });

        // Event handlers to trigger table reload when inputs change
        $('#from_date,#to_date,#customer_select, #project_select,#paymentStatus,#filterBranch').on('change', function() {
            tbl.ajax.reload(); // Reload the DataTable
        });

        function viewItem(id) {
            const url = route('employee-salaries.payslip.view', {
                salarySheet: id
            });
            window.location.href = url;
        }

        function downloadItem(id) {

        }
    </script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for customer and project selects
            $('#customer_select').select2({
                width: '100%',
                allowClear: true
            });
            $('#project_select').select2({
                width: '100%',
                allowClear: true
            });

            // Store all projects with customer_id as a JavaScript object
            var projects = @json($projects); // Convert Laravel collection to JSON object

            // Function to populate project dropdown based on selected customer
            function updateProjects(customerId) {
                var projectSelect = $('#project_select');
                projectSelect.empty(); // Clear the project dropdown

                // Add a default 'All' option if no customer is selected
                projectSelect.append($('<option>', {
                    value: '',
                    text: '{{ __('messages.customer.all') }}'
                }));

                // If a customer is selected, filter and populate the projects
                if (customerId) {
                    var filteredProjects = projects.filter(function(project) {
                        return project.customer_id == customerId;
                    });

                    // Add the filtered projects to the project dropdown
                    $.each(filteredProjects, function(index, project) {
                        projectSelect.append($('<option>', {
                            value: project.id,
                            text: project.project_name
                        }));
                    });

                    // Automatically select the first project if there is only one
                    if (filteredProjects.length === 1) {
                        projectSelect.val(filteredProjects[0].id).trigger('change');
                    }
                }

                // Refresh Select2 to show updated options
                projectSelect.trigger('change.select2');
            }

            // On customer select change, update the project dropdown
            $('#customer_select').on('change', function() {
                var customerId = $(this).val();
                updateProjects(customerId); // Update project dropdown based on selected customer
            });

            // Trigger an update on page load in case customer is preselected
            updateProjects($('#customer_select').val());
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#export-pdf').on('click', function(e) {
                e.preventDefault();

                // Get the values from form inputs
                // Get the values from form inputs (adjust IDs to match your form)
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('#customer_select').val();
                var projectId = $('#project_select').val();
                var paymentStatus = $('#paymentStatus').val();
                var filterBranch = $("#filterBranch").val();

                // Check if employee ID is selected
                if (!customerId) {
                    displayErrorMessage("Please select an Customer before exporting.");
                    return; // Stop further execution
                }

                // Construct the URL for PDF export
                var exportUrl = "{{ route('customer-statement.export') }}" +
                    "?type=pdf" +
                    "&from_date=" + encodeURIComponent(fromDate) +
                    "&to_date=" + encodeURIComponent(toDate) +
                    "&customer_select=" + encodeURIComponent(customerId) +
                    "&project_select=" + encodeURIComponent(projectId) +
                    "&filterBranch=" + encodeURIComponent(filterBranch) +
                    "&paymentStatus=" + encodeURIComponent(paymentStatus);

                // Redirect to the constructed URL
                window.location.href = exportUrl;
            });

            // Handle CSV export
            $('#export-csv').on('click', function(e) {
                e.preventDefault();

                // Get the values from form inputs
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var customerId = $('#customer_select').val();
                var projectId = $('#project_select').val();
                var paymentStatus = $('#paymentStatus').val();
                var filterBranch = $("#filterBranch").val();


                // Check if employee ID is selected
                if (!customerId) {
                    displayErrorMessage("Please select an Customer before exporting.");
                    return; // Stop further execution
                }

                // Construct the URL for CSV export
                var exportUrl = "{{ route('customer-statement.export') }}" +
                    "?type=csv" +
                    "&from_date=" + encodeURIComponent(fromDate) +
                    "&to_date=" + encodeURIComponent(toDate) +
                    "&customer_select=" + encodeURIComponent(customerId) +
                    "&project_select=" + encodeURIComponent(projectId) +
                    "&filterBranch=" + encodeURIComponent(filterBranch) +
                    "&paymentStatus=" + encodeURIComponent(paymentStatus);


                // Redirect to the constructed URL
                window.location.href = exportUrl;
            });

        });
    </script>
@endsection
