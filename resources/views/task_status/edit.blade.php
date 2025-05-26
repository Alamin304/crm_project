@extends('layouts.app')
@section('title')
    {{ __('messages.task-status.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.task-status.edit') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('task-status.index') }}"
                    class="btn btn-primary form-btn">{{ __('messages.task-status.list') }} </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    {{ Form::open(['id' => 'editTaskForm']) }}
                    {{ Form::hidden('id', $task->id, ['id' => 'designation_id']) }}

                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group  col-md-6">
                            {{ Form::label('admin_note', 'Branch') }}<span class="required">*</span>
                            {{ Form::select('branch_id', $usersBranches ?? [], $task->branch_id ?? null, ['class' => 'form-control select2', 'required', 'id' => 'branchSelect', 'placeholder' => 'Select Branch']) }}
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('employee_id', __('messages.attendances.select_iqama') . ':') }}<span
                                class="required">*</span>
                            {{ Form::select(
                                'user_id',
                                $employees->mapWithKeys(function ($employee) {
                                    return [$employee->id => $employee->iqama_no . ' (' . $employee->name . ')'];
                                }),
                                $task->user_id ?? null,
                                [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'employee_select',
                                    'placeholder' => __('messages.attendances.select_iqama'),
                                ],
                            ) }}
                        </div>
                        <!-- User Selection -->
                        {{-- <div class="form-group col-md-6 col-sm-12">
                            {{ Form::label('user_id', __('messages.task-status.user')) }}<span class="required">*</span>
                            {{ Form::select('user_id', $users, $task->user_id ?? null, ['class' => 'form-control select2', 'required', 'id' => 'user_id', auth()->user()->is_admin ? '' : 'disabled']) }}

                        </div> --}}
                        <div class="form-group col-md-6 col-md-6">
                            {{ Form::label('date', __('messages.task-status.date')) }}<span class="required">*</span>
                            {{ Form::date('date', isset($task) ? $task->date : \Carbon\Carbon::today()->toDateString(), ['class' => 'form-control', 'required', 'id' => 'task_date']) }}
                        </div>
                        <div class="form-group col-md-6 ">
                            {{ Form::label('duration', __('messages.task-status.duration')) }}<span
                                class="required">*</span>
                            {{ Form::number('duration', $task->duration ?? '', ['class' => 'form-control', 'required', 'id' => 'task_duration', 'step' => '0.01']) }}
                        </div>

                        <div class="form-group col-sm-12">
                            {{ Form::label('task', __('messages.task-status.task')) }}<span class="required">*</span>
                            {{ Form::textarea('task', $task->task ?? '', ['class' => 'form-control', 'required', 'id' => 'task_name', 'style' => 'height: 80px;']) }}

                        </div>
                        <div class="form-group col-sm-12 mb-0">
                            {{ Form::label('description', __('messages.task-status.description')) }}
                            {{ Form::textarea('description', $task->description ?? '', ['class' => 'form-control summernote-simple', 'id' => 'task_description']) }}
                        </div>

                        <div class="form-group col-md-6 col-sm-12">
                            {{ Form::label('customer_id', __('messages.task-status.customer')) }}<span
                                class="required">*</span>
                            {{ Form::select('customer_id', $customers, $task->customer_id ?? null, ['class' => 'form-control select2', 'required', 'id' => 'customer_id', 'placeholder' => __('messages.placeholder.select_customer')]) }}
                        </div>

                        <!-- Project Selection -->
                        <div class="form-group col-md-6 col-sm-12">
                            {{ Form::label('project_id', __('messages.task-status.project')) }}<span
                                class="required">*</span>
                            {{ Form::select('project_id', $projects->pluck('project_name', 'id'), $task->project_id ?? null, ['class' => 'form-control select2', 'required', 'id' => 'project_id', 'placeholder' => __('messages.placeholder.select_project')]) }}
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
        function processingBtn(buttonSelector, state) {
            var $button = $(buttonSelector);
            if (state === 1) {
                // Show loading and disable button
                $button.prop('disabled', true); // Disable the button
                $button.html('processing...'); // Change button text to indicate loading
            } else if (state === 0) {
                // Reset button state
                $button.prop('disabled', false); // Enable the button
                $button.html('Submit'); // Reset button text
            }
        }
    </script>
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


        });

        $(document).on('submit', '#editTaskForm', function(event) {
            event.preventDefault();
            processingBtn('#btnSave', 1);

            // Description validation
            let description = $('<div />').html($('#task_description').summernote('code'));
            let descriptionEmpty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#task_description').summernote('isEmpty')) {
                $('#task_description').val('');
            } else if (descriptionEmpty) {
                displayErrorMessage('Description field should not contain only white space.');
                processingBtn('#btnSave', 0);
                return false;
            }

            // Perform AJAX request
            $.ajax({
                url: '{{ route('task-status.update', $task->id) }}', // Ensure the correct URL is set with task ID
                type: 'PUT', // Use PUT for updating
                data: $(this).serialize(), // Serialize form data
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        const url = route(
                            'task-status.index'); // Ensure this is the correct redirection route
                        window.location.href = url;
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#btnSave', 0);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to set the hidden input field
            function setProjectName() {
                var selectedProjectName = $('#project_id').find('option:selected').text();
                $('#project_name').val(selectedProjectName);
            }

            // Set project name on page load
            setProjectName();

            // Update project name on change
            $('#project_id').change(function() {
                setProjectName();
            });
        });
    </script>

    <script>
        var projects = @json($projects);
        $(document).ready(function() {
            // Initialize Select2 for better dropdown styling and search
            $('#customer_id, #project_id').select2();


            // Event listener for customer selection change
            $('#customer_id').on('change', function() {
                var customerId = $(this).val(); // Get selected customer ID

                // Clear the project dropdown
                $('#project_id').empty().append(
                    '<option value="">{{ __('messages.placeholder.select_project') }}</option>');

                if (customerId) {
                    // Filter the projects based on selected customer ID
                    var filteredProjects = projects.filter(function(project) {
                        return project.customer_id == customerId;
                    });

                    // Populate the project dropdown with the filtered projects
                    $.each(filteredProjects, function(index, project) {
                        $('#project_id').append('<option value="' + project.id + '">' + project
                            .project_name + '</option>');
                    });

                    $('#project_id').trigger('change'); // Update Select2
                }
            });
        });
    </script>
@endsection
