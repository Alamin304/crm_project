@extends('layouts.app')

@section('title')
    {{ __('messages.job_categories.add_job_categories') }}
@endsection

@section('page_css')

    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
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
            <h1>{{ __('messages.job_categories.add_job_categories') }}</h1>
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
                        {{ Form::open(['id' => 'addNewJobCategoryForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.job_categories.name') . ':') }}<span class="required">*</span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'jobCategoryName', 'autocomplete' => 'off']) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.job_categories.description') . ':') }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'jobCategoryDescription']) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('start_date', __('messages.job_categories.start_date').':') }}<span class="required">*</span>
                                    {{ Form::date('start_date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'startDate'
                                    ]) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('end_date', __('messages.job_categories.end_date').':') }}<span class="required">*</span>
                                    {{ Form::date('end_date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'endDate'
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12 mt-3">
                                    {{ Form::label('status', __('messages.job_categories.status') . ':') }}<span class="required">*</span>
                                    <div class="form-check">
                                        {{ Form::radio('status', 1, true, ['class' => 'form-check-input', 'id' => 'active']) }}
                                        {{ Form::label('active', 'Active', ['class' => 'form-check-label']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('status', 0, false, ['class' => 'form-check-input', 'id' => 'inactive']) }}
                                        {{ Form::label('inactive', 'Inactive', ['class' => 'form-check-label']) }}
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
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let jobCategoryCreateUrl = "{{ route('job-categories.store') }}";

        $(function () {
            // Datepicker init
            $('#startDate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto',
                zIndexOffset: 9999
            }).on('changeDate', function (selected) {
                let minDate = new Date(selected.date.valueOf());
                $('#endDate').datepicker('setStartDate', minDate);
            });

            $('#endDate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom auto',
                zIndexOffset: 9999
            });

            // Summernote init
            $('#jobCategoryDescription').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });

        $(document).on('submit', '#addNewJobCategoryForm', function (event) {
            event.preventDefault();
            processingBtn('#addNewJobCategoryForm', '#btnSave', 'loading');

            let plainTextDescription = $('<div />').html($('#jobCategoryDescription').summernote('code')).text().trim();

            let formData = {
                name: $('#jobCategoryName').val(),
                description: plainTextDescription,
                start_date: $('#startDate').val(),
                end_date: $('#endDate').val(),
                status: $('input[name="status"]:checked').val(),
                _token: $('input[name="_token"]').val(),
            };

            if (!formData.name || !formData.start_date) {
                displayErrorMessage('Name and Start Date are required fields.');
                processingBtn('#addNewJobCategoryForm', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: jobCategoryCreateUrl,
                type: 'POST',
                data: formData,
                success: function (result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('job-categories.index') }}";
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message || 'An error occurred while saving the job category.');
                },
                complete: function () {
                    processingBtn('#addNewJobCategoryForm', '#btnSave');
                }
            });
        });
    </script>
@endsection
