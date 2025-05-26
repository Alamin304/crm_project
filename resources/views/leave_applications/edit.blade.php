@extends('layouts.app')
@section('title')
    {{ __('messages.leave-applications.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.leave-applications.edit') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('leave-applications.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.leave-applications.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['id' => 'editFormNew', 'enctype' => 'multipart/form-data']) }}
                    {{ Form::hidden('id', $leaveApplication->id, ['id' => 'leave_application_id']) }}
                    <div class="row">
                        <div class="form-group col-md-6">
                            {{ Form::label('admin_note', 'Branch') }}<span class="required">*</span>
                            {{ Form::select('branch_id', $usersBranches ?? [], $leaveApplication->branch_id ?? null, ['class' => 'form-control select2', 'required', 'id' => 'branchSelect', 'placeholder' => 'Select Branch']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('employee_id', __('messages.attendances.select_iqama') . ':') }}<span
                                class="required">*</span>
                            {{ Form::select(
                                'employee_id',
                                $employees->mapWithKeys(function ($employee) {
                                    return [$employee->id => $employee->iqama_no . ' (' . $employee->name . ')'];
                                }),
                                $leaveApplication->employee_id ?? null,
                                [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'employee_select',
                                    'placeholder' => __('messages.attendances.select_iqama'),
                                ],
                            ) }}
                        </div>
                        <div class="form-group col-sm-12 ">
                            <div class="row">
                                <div class="col-md-4">
                                    <p id="employeeName" class="text-black"></p>
                                    <p id="employeeDesignation" class="text-black"></p>
                                    {{-- <p id="employeeSubDepartment" class="text-black"></p>
                                        <p id="employeeDepartment" class="text-black"></p> --}}
                                </div>
                                <div class="col-md-3 col-sm-12 image_preview">
                                    <img id="employeeImage" src="" alt="Employee Image"
                                        style="display: none; max-width: 200px; height: auto; border-radius: 5px;" />
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('from_date', __('messages.leave-applications.from_date') . ':') }}<span
                                    class="required">*</span>
                                {{ Form::date('from_date', $leaveApplication->from_date ?? null, ['class' => 'form-control', 'id' => 'from_date', 'required', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('end_date', __('messages.leave-applications.end_date') . ':') }}<span
                                    class="required">*</span>
                                {{ Form::date('end_date', $leaveApplication->end_date ?? null, ['class' => 'form-control', 'id' => 'end_date', 'required', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('total_days', __('messages.leave-applications.total_days') . ':') }}
                                {{ Form::number('total_days', $leaveApplication->total_days ?? null, ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'readonly']) }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('leave_id', __('messages.leave-applications.leave_type') . ':') }}<span
                                    class="required">*</span>
                                {{ Form::select('leave_id', $leaves, $leaveApplication->leave_id ?? null, ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'id' => 'leave_type', 'placeholder' => __('messages.leave-applications.leave_type')]) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('paid Leave Days', __('messages.leave-applications.paid_leave_days')) }}<span
                                    class="required">*</span>
                                {{ Form::number('paid_leave_days', $leaveApplication->paid_leave_days ?? 0, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('paid Leave Days', __('messages.leave-applications.paid_leave_amount')) }}
                                {{ Form::number('paid_leave_amount', $leaveApplication->paid_leave_amount ?? 0, ['class' => 'form-control', 'id' => 'paid_leave_amount', 'required', 'autocomplete' => 'off', 'readonly']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('paid Leave Days', __('messages.leave-applications.ticket_amount')) }}
                                {{ Form::number('ticket_amount', $leaveApplication->ticket_amount ?? 0, ['class' => 'form-control', 'id' => 'ticket_amount', 'required', 'autocomplete' => 'off']) }}
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group">
                                {{ Form::label('paid Leave Days', __('messages.leave-applications.claim_amount')) }}
                                {{ Form::number('claim_amount', $leaveApplication->claim_amount ?? 0, ['class' => 'form-control', 'id' => 'claim_amount', 'required', 'autocomplete' => 'off', 'readonly']) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="form-group">
                                {{ Form::label('hard_copy', __('messages.leave-applications.hard_copy') . ':') }}
                                {{ Form::file('hard_copy', ['class' => 'form-control', 'id' => 'hard_copy', 'accept' => 'image/*,application/pdf']) }}

                            </div>
                        </div>
                        <div class="col-lg-6 col-12">
                            <!-- col-lg-6 for large screens, col-12 for extra small screens -->
                            <div class="form-group">
                                {{ Form::label('title', __('messages.leave-applications.reason') . ':') }}
                                {{ Form::textarea('description', $leaveApplication->description ?? null, ['class' => 'form-control summernote-simple', 'id' => 'createDescription', 'placeholder' => __('messages.assets.asset_note')]) }}
                            </div>
                        </div>
                    </div>

                    <div class="text-right mr-1">
                        {{ Form::button(__('messages.common.submit'), [
                            'type' => 'submit',
                            'class' => 'btn btn-primary btn-sm form-btn',
                            'id' => 'btnSave',
                            'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",
                        ]) }}

                    </div>

                    {{ Form::close() }}

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
        const allEmployees = {!! json_encode(
            $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'branch_id' => $employee->branch_id,
                    'iqama_no' => $employee->iqama_no,
                    'name' => $employee->name,
                ];
            }),
        ) !!};

        $('#branchSelect').on('change', function() {
            const selectedBranchId = $(this).val(); // Get the selected branch ID
            const $employeeSelect = $('#employee_select'); // Reference the employee select dropdown

            // Clear the current options in the employee select dropdown
            $employeeSelect.empty();
            $employeeSelect.append('<option value="">' + "{{ __('messages.attendances.select_iqama') }}" +
                '</option>');

            // Filter employees by branch_id and populate the dropdown
            allEmployees.forEach(function(employee) {
                if (employee.branch_id == selectedBranchId) {
                    $employeeSelect.append(
                        '<option value="' + employee.id + '">' + employee.iqama_no + ' (' + employee
                        .name + ')</option>'
                    );
                }
            });

            // Trigger a change event to update any dependent functionality
            $employeeSelect.trigger('change');
        });


        $(document).on('submit', '#editFormNew', function(e) {
            e.preventDefault();
            processingBtn('#editFormNew', '#btnSave', 'loading');
            let id = $('#leave_application_id').val();
            var formData = new FormData(this);
            $.ajax({
                type: 'post',
                url: route('leave-applications.update', id),
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        const url = route('leave-applications.index');
                        window.location.href = url;
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    processingBtn('#editFormNew', '#btnSave');
                },
                complete: function() {
                    processingBtn('#editFormNew', '#btnSave');
                },
            });
        });



        $(document).ready(function() {

            function calculateTotalDays() {
                var fromDate = $('#from_date').val();
                var endDate = $('#end_date').val();

                if (fromDate && endDate) {
                    // Convert the date strings into Date objects
                    var start = new Date(fromDate);
                    var end = new Date(endDate);

                    // Calculate the difference in time
                    var differenceInTime = end.getTime() - start.getTime();

                    // Convert the difference in time to days
                    var differenceInDays = differenceInTime / (1000 * 3600 * 24);

                    // Include both start and end dates
                    var totalDays = differenceInDays + 1;

                    // Set the total days in the input field
                    $("#total_days").val(totalDays);

                    // Check if total days is valid (greater than 0)
                    if (totalDays > 0) {
                        $('#btnSave').prop('disabled', false); // Enable the button
                    } else {
                        $('#btnSave').prop('disabled', true); // Disable the button
                    }
                } else {
                    $('#btnSave').prop('disabled', true); // Disable the button if dates are not selected
                }
            }
            // Trigger the calculation when the date fields change
            $('#from_date, #end_date').on('change', function() {
                calculateTotalDays();
            });

            $('#employee_select').select2({
                width: '100%', // Set the width of the select element
                placeholder: '{{ __('messages.leave-applications.select_employee') }}', // Placeholder text
                allowClear: true // Allow clearing the selection
            });
            $('#leave_type').select2({
                width: '100%', // Set the width of the select element
                placeholder: '{{ __('messages.leave-applications.leave_type') }}', // Placeholder text
                allowClear: true // Allow clearing the selection
            });


        });
    </script>

    <script>
        $(document).ready(function() {
            // Employee data stored in a JavaScript object
            var employees = @json($employees);
            var totalLeaves = @json($totalLeaves);

            $('#employee_select').change(function() {
                var selectedEmployeeId = $(this).val();
                var employee = employees.find(emp => emp.id == selectedEmployeeId);

                if (employee) {
                    var paidLeaveAmount = totalLeaves * (employee.basic_salary / 30);
                    $('#paid_leave_amount').val(paidLeaveAmount.toFixed(2));

                    if (employee.image) {
                        var imageUrl = "{{ asset('uploads/public/employee_images/') }}/" + employee.image;
                        $('#employeeImage').attr('src', imageUrl).show();
                    } else {
                        $('#employeeImage').hide();
                    }

                    // Update employee details
                    $('#employeeName').text('{{ __('messages.allowances.employee_name') }} : ' + employee
                        .name);
                    // $('#employeeDepartment').text(
                    //     '{{ __('messages.allowances.employee_department') }} : ' + employee
                    //     .department.name);
                    // $('#employeeSubDepartment').text(
                    //     '{{ __('messages.allowances.employee_sub_department') }} : ' + employee
                    //     .sub_department.name);
                    $('#employeeDesignation').text(
                        '{{ __('messages.allowances.employee_designation') }} : ' + (
                            employee.designation ? employee
                            .designation.name : 'N/A'));
                } else {
                    $('#employeeImage').hide();
                    $('#employeeName').text('');
                    $('#employeeDesignation').text('');
                    // $('#employeeSubDepartment').text('');
                    // $('#employeeDepartment').text('');
                }


            });

            $('#ticket_amount').keyup(function() {
                var ticketAmount = parseFloat($(this).val()) || 0;
                var paidLeaveAmount = parseFloat($('#paid_leave_amount').val()) || 0;
                var claimAmount = ticketAmount + paidLeaveAmount;
                $('#claim_amount').val(claimAmount.toFixed(2));
            });

            // Initialize with default selected employee details if available
            var defaultEmployeeId = $('#employee_select').val();
            if (defaultEmployeeId) {
                var defaultEmployee = employees.find(emp => emp.id == defaultEmployeeId);
                if (defaultEmployee) {
                    // Update image preview
                    if (defaultEmployee.image) {
                        var imageUrl = "{{ asset('uploads/public/employee_images/') }}/" + defaultEmployee.image;
                        $('#employeeImage').attr('src', imageUrl).show();
                    }

                    // Update employee details
                    $('#employeeName').text('Name: ' + defaultEmployee.name);
                    $('#employeeDesignation').text('Designation: ' + (defaultEmployee.designation ? defaultEmployee
                        .designation.name : 'N/A'));
                    // $('#employeeDepartment').text(
                    //     '{{ __('messages.allowances.employee_department') }} : ' + defaultEmployee
                    //     .department.name);
                    // $('#employeeSubDepartment').text(
                    //     '{{ __('messages.allowances.employee_sub_department') }} : ' + defaultEmployee
                    //     .sub_department.name);
                }
            }
        });
    </script>
@endsection
