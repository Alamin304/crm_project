@extends('layouts.app')

@section('title')
    {{ __('messages.training_programs.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .additional-fields {
            display: none;
        }

        .show-additional-fields .additional-fields {
            display: block;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.training_programs.edit') }}</h1>
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
                        {{ Form::open(['id' => 'editTrainingProgramForm', 'files' => true]) }}
                        {{ Form::hidden('id', $trainingProgram->id, ['id' => 'training_program_id']) }}

                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('program_name', __('messages.training_programs.training_name') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('program_name', $trainingProgram->program_name, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'programName',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('training_type', __('messages.training_programs.training_type') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('training_type', $trainingProgram->training_type, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'trainingType',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('program_items', __('Program Items') . ':') }}<span
                                        class="required">*</span>
                                    <select name="program_items[]" id="programItems" class="form-control select2" multiple
                                        required>
                                        @foreach (['Workshop', 'Seminar', 'On-the-job', 'E-learning', 'Conference'] as $item)
                                            <option value="{{ $item }}"
                                                {{ in_array($item, json_decode($trainingProgram->program_items, true)) ? 'selected' : '' }}>
                                                {{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('point', __('messages.training_programs.point') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::number('point', $trainingProgram->point, [
                                        'class' => 'form-control',
                                        'required',
                                        'min' => 0,
                                        'id' => 'point',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('training_mode', __('Training Mode') . ':') }}
                                    {{ Form::select(
                                        'training_mode',
                                        [
                                            'online' => 'Online',
                                            'offline' => 'Offline',
                                            'hybrid' => 'Hybrid',
                                        ],
                                        $trainingProgram->training_mode,
                                        [
                                            'class' => 'form-control select2',
                                            'id' => 'trainingMode',
                                            'placeholder' => __('Select Training Mode'),
                                        ],
                                    ) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="additionalStaffTraining"
                                            {{ $trainingProgram->staff_name ? 'checked' : '' }}>
                                        <label class="custom-control-label"
                                            for="additionalStaffTraining">{{ __('Additional Training Program for Staff') }}</label>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 additional-fields"
                                    style="{{ $trainingProgram->staff_name ? 'display:block' : '' }}">
                                    {{ Form::label('staff_name', __('messages.training_programs.name') . ':') }}
                                    {{ Form::text('staff_name', $trainingProgram->staff_name, [
                                        'class' => 'form-control',
                                        'id' => 'staffName',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6 additional-fields"
                                    style="{{ $trainingProgram->staff_name ? 'display:block' : '' }}">
                                    {{ Form::label('start_date', __('messages.training_programs.start_date') . ':') }}
                                    {{ Form::date('start_date', $trainingProgram->start_date, [
                                        'class' => 'form-control',
                                        'id' => 'startDate',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6 additional-fields"
                                    style="{{ $trainingProgram->staff_name ? 'display:block' : '' }}">
                                    {{ Form::label('finish_date', __('messages.training_programs.end_date') . ':') }}
                                    {{ Form::date('finish_date', $trainingProgram->finish_date, [
                                        'class' => 'form-control',
                                        'id' => 'finishDate',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12" id="departmentField"
                                    style="{{ $trainingProgram->staff_name ? 'display:none' : '' }}">
                                    {{ Form::label('departments', __('Departments') . ':') }}<span
                                        class="required">*</span>
                                    <select name="departments[]" id="departments" class="form-control select2" multiple
                                        {{ $trainingProgram->staff_name ? '' : 'required' }}>
                                        @foreach (['HR', 'IT', 'Finance', 'Marketing', 'Operations'] as $dept)
                                            <option value="{{ $dept }}"
                                                {{ in_array($dept, json_decode($trainingProgram->departments, true)) ? 'selected' : '' }}>
                                                {{ $dept }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @php
                                    $positionAttributes = [
                                        'class' => 'form-control',
                                        'id' => 'applyPosition',
                                    ];

                                    if (empty($trainingProgram->staff_name)) {
                                        $positionAttributes['required'] = 'required';
                                    }
                                @endphp

                                <div class="form-group col-sm-12" id="positionField"
                                    style="{{ $trainingProgram->staff_name ? 'display:none' : '' }}">
                                    {{ Form::label('apply_position', __('Apply Position') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('apply_position', $trainingProgram->apply_position, $positionAttributes) }}
                                </div>


                                <div class="form-group col-sm-12">
                                    {{ Form::label('description', __('messages.training_programs.description') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::textarea('description', $trainingProgram->description, [
                                        'class' => 'form-control summernote',
                                        'required',
                                        'id' => 'description',
                                        'rows' => 4,
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('attachment', __('Attachment') . ':') }}
                                    {{ Form::file('attachment', [
                                        'class' => 'form-control',
                                        'id' => 'attachment',
                                        'accept' => '.pdf,.doc,.docx,.jpg,.png',
                                    ]) }}
                                    @if ($trainingProgram->attachment)
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($trainingProgram->attachment) }}" target="_blank">
                                                {{ __('View Current Attachment') }}
                                            </a>
                                        </div>
                                    @endif
                                    <small
                                        class="text-muted">{{ __('Allowed file types: pdf, doc, docx, jpg, png') }}</small>
                                </div>
                            </div>

                            <div class="text-right mt-3">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
                                    'id' => 'btnSave',
                                    'data-loading-text' =>
                                        "<span class='spinner-border spinner-border-sm'></span> " . __('messages.common.processing') . '...',
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
        'use strict';

        $(document).ready(function() {
            // Initialize summernote
            $('.summernote').summernote({
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            // Initialize select2
            $('.select2').select2();

            // Toggle additional fields
            $('#additionalStaffTraining').change(function() {
                if ($(this).is(':checked')) {
                    $('.additional-fields').show();
                    $('#departmentField, #positionField').hide();
                    $('#departments, #applyPosition').removeAttr('required');
                } else {
                    $('.additional-fields').hide();
                    $('#departmentField, #positionField').show();
                    $('#departments, #applyPosition').attr('required', 'required');
                }
            });

            // Set initial state based on existing data
            if ($('#additionalStaffTraining').is(':checked')) {
                $('.additional-fields').show();
                $('#departmentField, #positionField').hide();
                $('#departments, #applyPosition').removeAttr('required');
            }
        });

        $(document).on('submit', '#editTrainingProgramForm', function(event) {
            event.preventDefault();
            processingBtn('#editTrainingProgramForm', '#btnSave', 'loading');
            let id = $('#training_program_id').val();
            let formData = new FormData(this);

            // Add _method to FormData
            formData.append('_method', 'PUT');

            $.ajax({
                url: route('training-programs.update', id),
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
                    processingBtn('#editTrainingProgramForm', '#btnSave');
                }
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
