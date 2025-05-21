@extends('layouts.app')

@section('title')
    {{ __('messages.training_programs.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .additional-fields {
            display: none;
            transition: all 0.3s ease;
        }
        .show-additional-fields .additional-fields {
            display: block;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.training_programs.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('training-programs.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.training_programs.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewTrainingProgramForm', 'files' => true]) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('program_name', __('messages.training_programs.training_name').':') }}<span class="required">*</span>
                                    {{ Form::text('program_name', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'programName',
                                        'placeholder' => __('messages.training_programs.training_name'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('training_type', __('messages.training_programs.training_type').':') }}<span class="required">*</span>
                                    {{ Form::text('training_type', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'trainingType',
                                        'placeholder' => __('messages.training_programs.training_type'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('program_items', __('Program Items').':') }}<span class="required">*</span>
                                    <select name="program_items[]" id="programItems" class="form-control select2" multiple required>
                                        @foreach(['Workshop', 'Seminar', 'On-the-job', 'E-learning', 'Conference'] as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('point', __('messages.training_programs.point').':') }}<span class="required">*</span>
                                    {{ Form::number('point', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 0,
                                        'id' => 'point',
                                        'placeholder' => __('messages.training_programs.point')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('training_mode', __('Training Mode').':') }}
                                    {{ Form::select('training_mode', [
                                        'online' => 'Online',
                                        'offline' => 'Offline',
                                        'hybrid' => 'Hybrid'
                                    ], null, [
                                        'class' => 'form-control select2',
                                        'id' => 'trainingMode',
                                        'placeholder' => __('Select Training Mode')
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="additionalStaffTraining" name="additional_staff_training">
                                        <label class="custom-control-label" for="additionalStaffTraining">{{ __('Additional Training Program for Staff') }}</label>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 additional-fields">
                                    {{ Form::label('staff_name', __('messages.training_programs.name').':') }}
                                    {{ Form::text('staff_name', null, [
                                        'class' => 'form-control',
                                        'id' => 'staffName',
                                        'placeholder' => __('messages.training_programs.name'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6 additional-fields">
                                    {{ Form::label('start_date', __('messages.training_programs.start_date').':') }}
                                    {{ Form::date('start_date', null, [
                                        'class' => 'form-control',
                                        'id' => 'startDate'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6 additional-fields">
                                    {{ Form::label('finish_date', __('messages.training_programs.end_date').':') }}
                                    {{ Form::date('finish_date', null, [
                                        'class' => 'form-control',
                                        'id' => 'finishDate'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12" id="departmentField">
                                    {{ Form::label('departments', __('Departments').':') }}<span class="required">*</span>
                                    <select name="departments[]" id="departments" class="form-control select2" multiple required>
                                        @foreach(['HR', 'IT', 'Finance', 'Marketing', 'Operations'] as $dept)
                                            <option value="{{ $dept }}">{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-12" id="positionField">
                                    {{ Form::label('apply_position', __('Apply Position').':') }}<span class="required">*</span>
                                    {{ Form::text('apply_position', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'applyPosition',
                                        'placeholder' => __('Apply Position'),
                                        'autocomplete' => 'off'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.training_programs.description').':') }}<span class="required">*</span>
                                    {{ Form::textarea('description', null, [
                                        'class' => 'form-control summernote-simple',
                                        'required',
                                        'id' => 'description',
                                        'rows' => 4
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('attachment', __('Attachment').':') }}
                                    {{ Form::file('attachment', [
                                        'class' => 'form-control',
                                        'id' => 'attachment',
                                        'accept' => '.pdf,.doc,.docx,.jpg,.png'
                                    ]) }}
                                    <small class="text-muted">{{ __('Allowed file types: pdf, doc, docx, jpg, png') }}</small>
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
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let trainingProgramCreateUrl = "{{ route('training-programs.store') }}";

        $(document).ready(function() {
            $('#description').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            // Initialize select2
            $('.select2').select2();

            // Toggle additional fields
            $('#additionalStaffTraining').change(function() {
                if($(this).is(':checked')) {
                    $('form').addClass('show-additional-fields');
                    $('#departmentField, #positionField').hide();
                    $('#departments, #applyPosition').removeAttr('required');
                } else {
                    $('form').removeClass('show-additional-fields');
                    $('#departmentField, #positionField').show();
                    $('#departments, #applyPosition').attr('required', 'required');
                }
            });
        });

        $(document).on('submit', '#addNewTrainingProgramForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewTrainingProgramForm', '#btnSave', 'loading');

            let formData = new FormData(this);

            $.ajax({
                url: trainingProgramCreateUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('training-programs.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewTrainingProgramForm', '#btnSave');
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
