@extends('layouts.app')

@section('title')
    {{ __('messages.notice_boards.add_notice_boards') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.notice_boards.add_notice_boards') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('notice-boards.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.notice_boards.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormNotice']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('notice_type', __('messages.notice_boards.notice_type') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('notice_type', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'noticeType',
                                        'placeholder' => __('messages.notice_boards.notice_type'),
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('notice_by', __('messages.notice_boards.notice_by') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('notice_by', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'noticeBy',
                                        'placeholder' => __('messages.notice_boards.notice_by'),
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('notice_date', __('messages.notice_boards.notice_date') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::date('notice_date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'noticeDate',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.notice_boards.description') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::textarea('description', null, [
                                        'class' => 'form-control summernote-simple',
                                        'required',
                                        'id' => 'noticeDescription',
                                        'rows' => 4,
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('notice_attachment', __('messages.notice_boards.notice_attachment') . ':') }}
                                    {{ Form::file('notice_attachment', [
                                        'class' => 'form-control',
                                        'id' => 'noticeAttachment',
                                        'accept' => '.pdf,.doc,.docx,.jpg,.png',
                                    ]) }}
                                    <small class="text-muted">{{ __('messages.notice_boards.allowed_file_types') }}</small>
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-sm form-btn rounded-pill',
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
@endsection

@section('scripts')
    <script>
        let noticeCreateUrl = "{{ route('notice-boards.store') }}";

        $(document).ready(function() {
            $('#noticeDescription').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });

        $(document).on('submit', '#addNewFormNotice', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormNotice', '#btnSave', 'loading');

            let formData = new FormData(this);

            $.ajax({
                url: noticeCreateUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('notice-boards.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewFormNotice', '#btnSave');
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
