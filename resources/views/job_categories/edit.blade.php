@extends('layouts.app')

@section('title')
    {{ __('messages.job_categories.edit_job_categories') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <style>
        .datepicker {
            z-index: 1055 !important;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.job_categories.edit') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('job-categories.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.job_categories.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'editJobCategoryForm']) }}
                        {{ Form::hidden('id', $jobCategory->id, ['id' => 'jobCategoryId']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.job_categories.name') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('name', $jobCategory->name, ['class' => 'form-control', 'required', 'id' => 'editJobCategoryName', 'autocomplete' => 'off']) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.job_categories.description') . ':') }}
                                    {{ Form::textarea('description', $jobCategory->description, ['class' => 'form-control summernote-simple', 'id' => 'editJobCategoryDescription']) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('start_date', __('messages.job_categories.start_date') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::date('start_date', $jobCategory->start_date, ['class' => 'form-control', 'required', 'id' => 'editStartDate']) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('end_date', __('messages.job_categories.end_date') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::date('end_date', $jobCategory->end_date, ['class' => 'form-control', 'required', 'id' => 'editEndDate']) }}
                                </div>

                                <div class="form-group col-sm-12 mt-3">
                                    {{ Form::label('status', __('messages.job_categories.status') . ':') }}<span
                                        class="required">*</span>
                                    <div class="form-check">
                                        {{ Form::radio('status', 1, $jobCategory->status == 1, ['class' => 'form-check-input', 'id' => 'active']) }}
                                        {{ Form::label('active', 'Active', ['class' => 'form-check-label']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('status', 0, $jobCategory->status == 0, ['class' => 'form-check-input', 'id' => 'inactive']) }}
                                        {{ Form::label('inactive', 'Inactive', ['class' => 'form-check-label']) }}
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-sm form-btn',
                                    'id' => 'btnSave',
                                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",
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
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let updateUrl = "{{ route('job-categories.update', $jobCategory->id) }}";

        $(document).ready(function() {
            $('#editJobCategoryDescription').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('#editStartDate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto'
            }).on('changeDate', function(selected) {
                let minDate = new Date(selected.date.valueOf());
                $('#editEndDate').datepicker('setStartDate', minDate);
            });

            $('#editEndDate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto'
            });
        });

        $(document).on('submit', '#editJobCategoryForm', function(event) {
            event.preventDefault();
            processingBtn('#editJobCategoryForm', '#btnSave', 'loading');

            let description = $('#editJobCategoryDescription').summernote('code');

            let formData = {
                name: $('#editJobCategoryName').val(),
                description: description,
                start_date: $('#editStartDate').val(),
                end_date: $('#editEndDate').val(),
                status: $('input[name="status"]:checked').val(),
                _token: $('input[name="_token"]').val(),
                _method: 'PUT'
            };

            // Check for required fields
            if (!formData.name || !formData.start_date || !formData.end_date) {
                displayErrorMessage('Name, Start Date, and End Date are required.');
                processingBtn('#editJobCategoryForm', '#btnSave', 'reset');
                return false;
            }

            // Validate that description is not empty
            let tempDiv = document.createElement('div');
            tempDiv.innerHTML = description;
            let plainText = tempDiv.textContent || tempDiv.innerText || '';
            if (!plainText.trim()) {
                displayErrorMessage('Description cannot be empty.');
                processingBtn('#editJobCategoryForm', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: updateUrl,
                type: 'POST',
                data: formData,
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('job-categories.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message || 'An error occurred.');
                },
                complete: function() {
                    processingBtn('#editJobCategoryForm', '#btnSave');
                }
            });
        });
    </script>
@endsection
