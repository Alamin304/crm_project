@extends('layouts.app')

@section('title')
    {{ __('messages.plans.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.plans.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('plans.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.plans.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormPlan', 'files' => true]) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('plan_name', __('messages.plans.plan_name').':') }}<span class="required">*</span>
                                    {{ Form::text('plan_name', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'planName',
                                        'placeholder' => __('messages.plans.plan_name'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                 <!-- Position Field with Select Options -->
                                <div class="form-group col-sm-6">
                                    {{ Form::label('position', __('messages.plans.position').':') }}<span class="required">*</span>
                                    {{ Form::select('position', [
                                        'Software Engineer' => 'Software Engineer',
                                        'Senior Developer' => 'Senior Developer',
                                        'Project Manager' => 'Project Manager',
                                        'HR Manager' => 'HR Manager',
                                        'Marketing Specialist' => 'Marketing Specialist',
                                        'Sales Executive' => 'Sales Executive',
                                        'Accountant' => 'Accountant',
                                        'System Administrator' => 'System Administrator'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'position',
                                        'placeholder' => __('messages.plans.select_position')
                                    ]) }}
                                </div>

                                <!-- Department Field with Select Options -->
                                <div class="form-group col-sm-6">
                                    {{ Form::label('department', __('messages.plans.department').':') }}<span class="required">*</span>
                                    {{ Form::select('department', [
                                        'IT' => 'IT Department',
                                        'HR' => 'Human Resources',
                                        'Finance' => 'Finance',
                                        'Marketing' => 'Marketing',
                                        'Sales' => 'Sales',
                                        'Operations' => 'Operations',
                                        'Customer Support' => 'Customer Support',
                                        'Research & Development' => 'Research & Development'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'department',
                                        'placeholder' => __('messages.plans.select_department')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('recruited_quantity', __('messages.plans.recruited_quantity').':') }}<span class="required">*</span>
                                    {{ Form::number('recruited_quantity', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 1,
                                        'id' => 'recruitedQuantity',
                                        'placeholder' => __('messages.plans.recruited_quantity'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <!-- Working Form Field with Select Options -->
                                <div class="form-group col-sm-6">
                                    {{ Form::label('working_form', __('messages.plans.working_form').':') }}<span class="required">*</span>
                                    {{ Form::select('working_form', [
                                        'Full-time' => 'Full-time',
                                        'Part-time' => 'Part-time',
                                        'Contract' => 'Contract',
                                        'Freelance' => 'Freelance',
                                        'Internship' => 'Internship',
                                        'Remote' => 'Remote'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'workingForm',
                                        'placeholder' => __('messages.plans.select_working_form')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('workplace', __('messages.plans.workplace').':') }}<span class="required">*</span>
                                    {{ Form::text('workplace', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'workplace',
                                        'placeholder' => __('messages.plans.workplace'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('starting_salary_from', __('messages.plans.starting_salary_from').':') }}<span class="required">*</span>
                                    {{ Form::number('starting_salary_from', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'startingSalaryFrom',
                                        'placeholder' => __('messages.plans.starting_salary_from'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('starting_salary_to', __('messages.plans.starting_salary_to').':') }}<span class="required">*</span>
                                    {{ Form::number('starting_salary_to', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'startingSalaryTo',
                                        'placeholder' => __('messages.plans.starting_salary_to'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('from_date', __('messages.plans.from_date').':') }}<span class="required">*</span>
                                    {{ Form::date('from_date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'fromDate'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('to_date', __('messages.plans.to_date').':') }}<span class="required">*</span>
                                    {{ Form::date('to_date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'toDate'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('reason', __('messages.plans.reason').':') }}<span class="required">*</span>
                                    {{ Form::textarea('reason', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'reason',
                                        'rows' => 3,
                                        'placeholder' => __('messages.plans.reason')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('job_description', __('messages.plans.job_description').':') }}<span class="required">*</span>
                                    {{ Form::textarea('job_description', null, [
                                        'class' => 'form-control summernote',
                                        'required',
                                        'id' => 'jobDescription',
                                        'rows' => 4
                                    ]) }}
                                </div>

                                 <!-- Approver Field with Select Options -->
                                <div class="form-group col-sm-12">
                                    {{ Form::label('approver', __('messages.plans.approver').':') }}<span class="required">*</span>
                                    {{ Form::select('approver', [
                                        'John Smith' => 'John Smith (CEO)',
                                        'Sarah Johnson' => 'Sarah Johnson (HR Director)',
                                        'Michael Brown' => 'Michael Brown (Department Head)',
                                        'Emily Davis' => 'Emily Davis (Finance Manager)',
                                        'David Wilson' => 'David Wilson (Operations Director)'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'approver',
                                        'placeholder' => __('messages.plans.select_approver')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('age_from', __('messages.plans.age_from').':') }}
                                    {{ Form::number('age_from', null, [
                                        'class' => 'form-control',
                                        'min' => 18,
                                        'id' => 'ageFrom',
                                        'placeholder' => __('messages.plans.age_from'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('age_to', __('messages.plans.age_to').':') }}
                                    {{ Form::number('age_to', null, [
                                        'class' => 'form-control',
                                        'min' => 18,
                                        'id' => 'ageTo',
                                        'placeholder' => __('messages.plans.age_to'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('gender', __('messages.plans.gender').':') }}
                                    {{ Form::select('gender', [
                                        'male' => __('messages.plans.male'),
                                        'female' => __('messages.plans.female'),
                                        'other' => __('messages.plans.other')
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'id' => 'gender',
                                        'placeholder' => __('messages.plans.select_gender')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('height', __('messages.plans.height').':') }}
                                    {{ Form::number('height', null, [
                                        'class' => 'form-control',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'height',
                                        'placeholder' => __('messages.plans.height'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('weight', __('messages.plans.weight').':') }}
                                    {{ Form::number('weight', null, [
                                        'class' => 'form-control',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'weight',
                                        'placeholder' => __('messages.plans.weight'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('literacy', __('messages.plans.literacy').':') }}<span class="required">*</span>
                                    {{ Form::text('literacy', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'literacy',
                                        'placeholder' => __('messages.plans.literacy'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('seniority', __('messages.plans.seniority').':') }}<span class="required">*</span>
                                    {{ Form::text('seniority', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'seniority',
                                        'placeholder' => __('messages.plans.seniority'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('attachment', __('messages.plans.attachment').':') }}
                                    {{ Form::file('attachment', [
                                        'class' => 'form-control',
                                        'id' => 'attachment',
                                        'accept' => '.pdf,.doc,.docx,.jpg,.png'
                                    ]) }}
                                    <small class="text-muted">{{ __('messages.plans.allowed_file_types') }}</small>
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
                                    'id' => 'btnSave',
                                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."
                                ]) }}
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let planCreateUrl = "{{ route('plans.store') }}";

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            $('#jobDescription').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });

        $(document).on('submit', '#addNewFormPlan', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormPlan', '#btnSave', 'loading');

            let formData = new FormData(this);

            $.ajax({
                url: planCreateUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('plans.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewFormPlan', '#btnSave');
                },
            });
        });

        function processErrorMessage(errors) {
            let errorHtml = '<ul>';
            $.each(errors, function(key, value) {
                errorHtml += '<li>' + value[0] + '</li>';
            });
            errorHtml += '</ul>';
            $('#validationErrorsBox').html(errorHtml);
            $('#validationErrorsBox').removeClass('d-none');
        }
    </script>
@endsection
