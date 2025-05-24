@extends('layouts.app')

@section('title')
    {{ __('messages.campaigns.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.campaigns.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('campaigns.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.campaigns.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormCampaign', 'files' => true]) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('campaign_code', __('messages.campaigns.campaign_code').':') }}<span class="required">*</span>
                                    {{ Form::text('campaign_code', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'campaignCode',
                                        'placeholder' => __('messages.campaigns.campaign_code'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('campaign_name', __('messages.campaigns.campaign_name').':') }}<span class="required">*</span>
                                    {{ Form::text('campaign_name', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'campaignName',
                                        'placeholder' => __('messages.campaigns.campaign_name'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('recruitment_plan', __('messages.campaigns.recruitment_plan').':') }}<span class="required">*</span>
                                    {{ Form::text('recruitment_plan', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'recruitmentPlan',
                                        'placeholder' => __('messages.campaigns.recruitment_plan'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('recruitment_channel_from', __('messages.campaigns.recruitment_channel_from').':') }}<span class="required">*</span>
                                    {{ Form::select('recruitment_channel_from', [
                                        'LinkedIn' => 'LinkedIn',
                                        'Indeed' => 'Indeed',
                                        'Glassdoor' => 'Glassdoor',
                                        'Company Website' => 'Company Website',
                                        'Job Fair' => 'Job Fair',
                                        'Referral' => 'Referral',
                                        'Other' => 'Other'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'recruitmentChannelFrom',
                                        'placeholder' => __('messages.campaigns.select_recruitment_channel')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('position', __('messages.campaigns.position').':') }}<span class="required">*</span>
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
                                        'placeholder' => __('messages.campaigns.select_position')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('company', __('messages.campaigns.company').':') }}<span class="required">*</span>
                                    {{ Form::text('company', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'company',
                                        'placeholder' => __('messages.campaigns.company'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('recruited_quantity', __('messages.campaigns.recruited_quantity').':') }}<span class="required">*</span>
                                    {{ Form::number('recruited_quantity', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 1,
                                        'id' => 'recruitedQuantity',
                                        'placeholder' => __('messages.campaigns.recruited_quantity'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('working_form', __('messages.campaigns.working_form').':') }}<span class="required">*</span>
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
                                        'placeholder' => __('messages.campaigns.select_working_form')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('department', __('messages.campaigns.department').':') }}<span class="required">*</span>
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
                                        'placeholder' => __('messages.campaigns.select_department')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('workplace', __('messages.campaigns.workplace').':') }}<span class="required">*</span>
                                    {{ Form::text('workplace', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'workplace',
                                        'placeholder' => __('messages.campaigns.workplace'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('starting_salary_from', __('messages.campaigns.starting_salary_from').':') }}<span class="required">*</span>
                                    {{ Form::number('starting_salary_from', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'startingSalaryFrom',
                                        'placeholder' => __('messages.campaigns.starting_salary_from'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('starting_salary_to', __('messages.campaigns.starting_salary_to').':') }}<span class="required">*</span>
                                    {{ Form::number('starting_salary_to', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'startingSalaryTo',
                                        'placeholder' => __('messages.campaigns.starting_salary_to'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('from_date', __('messages.campaigns.from_date').':') }}<span class="required">*</span>
                                    {{ Form::date('from_date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'fromDate'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('to_date', __('messages.campaigns.to_date').':') }}<span class="required">*</span>
                                    {{ Form::date('to_date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'toDate'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('reason', __('messages.campaigns.reason').':') }}<span class="required">*</span>
                                    {{ Form::textarea('reason', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'reason',
                                        'rows' => 3,
                                        'placeholder' => __('messages.campaigns.reason')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('job_description', __('messages.campaigns.job_description').':') }}<span class="required">*</span>
                                    {{ Form::textarea('job_description', null, [
                                        'class' => 'form-control summernote',
                                        'required',
                                        'id' => 'jobDescription',
                                        'rows' => 4
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('managers', __('messages.campaigns.managers').':') }}
                                    {{ Form::select('managers[]', [
                                        'John Smith' => 'John Smith (CEO)',
                                        'Sarah Johnson' => 'Sarah Johnson (HR Director)',
                                        'Michael Brown' => 'Michael Brown (Department Head)',
                                        'Emily Davis' => 'Emily Davis (Finance Manager)',
                                        'David Wilson' => 'David Wilson (Operations Director)'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'id' => 'managers',
                                        'multiple' => 'multiple',
                                        'placeholder' => __('messages.campaigns.select_managers')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('followers', __('messages.campaigns.followers').':') }}
                                    {{ Form::select('followers[]', [
                                        'Robert Taylor' => 'Robert Taylor (HR)',
                                        'Jennifer Lee' => 'Jennifer Lee (Recruiter)',
                                        'Thomas Moore' => 'Thomas Moore (Team Lead)',
                                        'Jessica White' => 'Jessica White (HR Specialist)',
                                        'Daniel Clark' => 'Daniel Clark (Recruitment)'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'id' => 'followers',
                                        'multiple' => 'multiple',
                                        'placeholder' => __('messages.campaigns.select_followers')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('meta_title', __('messages.campaigns.meta_title').':') }}
                                    {{ Form::text('meta_title', null, [
                                        'class' => 'form-control',
                                        'id' => 'metaTitle',
                                        'placeholder' => __('messages.campaigns.meta_title'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('meta_description', __('messages.campaigns.meta_description').':') }}
                                    {{ Form::textarea('meta_description', null, [
                                        'class' => 'form-control',
                                        'id' => 'metaDescription',
                                        'rows' => 2,
                                        'placeholder' => __('messages.campaigns.meta_description')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('age_from', __('messages.campaigns.age_from').':') }}
                                    {{ Form::number('age_from', null, [
                                        'class' => 'form-control',
                                        'min' => 18,
                                        'id' => 'ageFrom',
                                        'placeholder' => __('messages.campaigns.age_from'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('age_to', __('messages.campaigns.age_to').':') }}
                                    {{ Form::number('age_to', null, [
                                        'class' => 'form-control',
                                        'min' => 18,
                                        'id' => 'ageTo',
                                        'placeholder' => __('messages.campaigns.age_to'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('gender', __('messages.campaigns.gender').':') }}
                                    {{ Form::select('gender', [
                                        'male' => __('messages.campaigns.male'),
                                        'female' => __('messages.campaigns.female'),
                                        'other' => __('messages.campaigns.other')
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'id' => 'gender',
                                        'placeholder' => __('messages.campaigns.select_gender')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('height', __('messages.campaigns.height').':') }}
                                    {{ Form::number('height', null, [
                                        'class' => 'form-control',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'height',
                                        'placeholder' => __('messages.campaigns.height'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('weight', __('messages.campaigns.weight').':') }}
                                    {{ Form::number('weight', null, [
                                        'class' => 'form-control',
                                        'min' => 0,
                                        'step' => '0.01',
                                        'id' => 'weight',
                                        'placeholder' => __('messages.campaigns.weight'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('literacy', __('messages.campaigns.literacy').':') }}<span class="required">*</span>
                                    {{ Form::text('literacy', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'literacy',
                                        'placeholder' => __('messages.campaigns.literacy'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('seniority', __('messages.campaigns.seniority').':') }}<span class="required">*</span>
                                    {{ Form::text('seniority', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'seniority',
                                        'placeholder' => __('messages.campaigns.seniority'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('attachment', __('messages.campaigns.attachment').':') }}
                                    {{ Form::file('attachment', [
                                        'class' => 'form-control',
                                        'id' => 'attachment',
                                        'accept' => '.pdf,.doc,.docx,.jpg,.png'
                                    ]) }}
                                    <small class="text-muted">{{ __('messages.campaigns.allowed_file_types') }}</small>
                                </div>

                                <div class="form-group col-sm-6">
                                    <div class="custom-control custom-checkbox">
                                        {{ Form::checkbox('is_active', 1, true, ['class' => 'custom-control-input', 'id' => 'isActive']) }}
                                        {{ Form::label('is_active', __('messages.campaigns.is_active'), ['class' => 'custom-control-label']) }}
                                    </div>
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
        let campaignCreateUrl = "{{ route('campaigns.store') }}";

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

        $(document).on('submit', '#addNewFormCampaign', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormCampaign', '#btnSave', 'loading');

            let formData = new FormData(this);

            $.ajax({
                url: campaignCreateUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('campaigns.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewFormCampaign', '#btnSave');
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
