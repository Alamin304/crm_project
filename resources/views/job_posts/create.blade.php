@extends('layouts.app')
@section('title')
    {{ __('messages.job_posts.add_job_post') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.job_posts.add_job_post') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('job-posts.index') }}"
                    class="btn btn-primary form-btn">{{ __('messages.job_posts.list') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addJobPostForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('company_name', __('messages.job_posts.company') . ':') }}<span
                                            class="required">*</span>
                                        <select class="form-control select2" id="company_name" name="company_name" required>
                                            <option value="">{{ __('messages.job_posts.select_company') }}</option>
                                            <option value="Tech Solutions Inc.">Tech Solutions Inc.</option>
                                            <option value="Digital Innovations LLC">Digital Innovations LLC</option>
                                            <option value="Web Crafters Ltd.">Web Crafters Ltd.</option>
                                            <option value="Data Systems Co.">Data Systems Co.</option>
                                            <option value="Cloud Networks Corp.">Cloud Networks Corp.</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('job_title', __('messages.job_posts.job_title') . ':') }}<span
                                            class="required">*</span>
                                        {{ Form::text('job_title', null, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('job_category_id', __('messages.job_posts.job_category') . ':') }}<span
                                            class="required">*</span>
                                        <select class="form-control select2" id="job_category_id" name="job_category_id"
                                            required>
                                            <option value="">{{ __('messages.job_posts.select_category') }}</option>
                                            @foreach ($categories as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('job_type', __('messages.job_posts.job_type') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::select(
                                        'job_type',
                                        [
                                            'full_time' => __('messages.job_posts.full_time'),
                                            'part_time' => __('messages.job_posts.part_time'),
                                            'contract' => __('messages.job_posts.contract'),
                                            'temporary' => __('messages.job_posts.temporary'),
                                            'internship' => __('messages.job_posts.internship'),
                                        ],
                                        null,
                                        ['class' => 'form-control select2', 'required'],
                                    ) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('no_of_vacancy', __('messages.job_posts.no_of_vacancy') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::number('no_of_vacancy', null, ['class' => 'form-control', 'required', 'min' => 1]) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('date_of_closing', __('messages.job_posts.date_of_closing') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::date('date_of_closing', null, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('gender', __('messages.job_posts.gender') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::select(
                                        'gender',
                                        [
                                            'male' => __('messages.job_posts.male'),
                                            'female' => __('messages.job_posts.female'),
                                            'any' => __('messages.job_posts.any'),
                                        ],
                                        null,
                                        ['class' => 'form-control select2', 'required'],
                                    ) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('minimum_experience', __('messages.job_posts.minimum_experience') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::number('minimum_experience', null, ['class' => 'form-control', 'required', 'min' => 0]) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('is_featured', __('messages.job_posts.is_featured') . ':') }}
                                    <div class="custom-control custom-checkbox">
                                        {{ Form::checkbox('is_featured', 1, false, ['class' => 'custom-control-input', 'id' => 'is_featured']) }}
                                        <label class="custom-control-label"
                                            for="is_featured">{{ __('messages.job_posts.featured') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('status', __('messages.job_posts.status') . ':') }}
                                    <div class="custom-control custom-checkbox">
                                        {{ Form::checkbox('status', 1, true, ['class' => 'custom-control-input', 'id' => 'status']) }}
                                        <label class="custom-control-label"
                                            for="status">{{ __('messages.job_posts.active') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('short_description', __('messages.job_posts.short_description') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::textarea('short_description', null, ['class' => 'form-control', 'rows' => 3, 'required']) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('long_description', __('messages.job_posts.long_description') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::textarea('long_description', null, ['class' => 'form-control summernote-simple', 'required']) }}
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
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let jobPostCreateUrl = "{{ route('job-posts.store') }}";

        $(document).ready(function() {
            // Initialize all select2 elements
            $('.select2').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                },
                allowClear: true
            });

            // Summernote initialization
            $('.summernote-simple').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['ul', 'ol']],
                ]
            });
        });

        $(document).on('submit', '#addJobPostForm', function(event) {
            event.preventDefault();
            processingBtn('#addJobPostForm', '#btnSave', 'loading');

            if ($('.summernote-simple').summernote('isEmpty')) {
                displayErrorMessage('Long description field is required');
                processingBtn('#addJobPostForm', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: jobPostCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('job-posts.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addJobPostForm', '#btnSave');
                },
            });
        });
    </script>
@endsection
