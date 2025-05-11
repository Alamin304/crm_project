@extends('layouts.app')

@section('title')
    {{ __('messages.notice_boards.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.notice_boards.edit') }}</h1>
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
                        {{ Form::open(['id' => 'editNoticeBoardForm', 'files' => true]) }}
                        {{ Form::hidden('id', $noticeBoard->id, ['id' => 'notice_board_id']) }}

                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('notice_type', __('messages.notice_boards.notice_type') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('notice_type', $noticeBoard->notice_type, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'noticeType',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('notice_by', __('messages.notice_boards.notice_by') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('notice_by', $noticeBoard->notice_by, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'noticeBy',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('notice_date', __('messages.notice_boards.notice_date') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::date('notice_date', $noticeBoard->notice_date, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'noticeDate',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('notice_attachment', __('messages.notice_boards.notice_attachment') . ':') }}
                                    {{ Form::file('notice_attachment', [
                                        'class' => 'form-control',
                                        'id' => 'noticeAttachment',
                                        'accept' => '.pdf,.doc,.docx,.jpg,.png',
                                    ]) }}
                                    @if ($noticeBoard->notice_attachment)
                                        <div class="mt-2">
                                            {{-- <a href="{{ asset('storage/' . $noticeBoard->notice_attachment) }}"
                                                target="_blank">
                                                {{ __('messages.common.view_attachment') }}
                                            </a> --}}
                                        </div>
                                    @endif
                                    <small class="text-muted">{{ __('messages.notice_boards.allowed_file_types') }}</small>
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('description', __('messages.notice_boards.description') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::textarea('description', $noticeBoard->description, [
                                        'class' => 'form-control summernote',
                                        'required',
                                        'id' => 'noticeDescription',
                                        'rows' => 4,
                                    ]) }}
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
@endsection

@section('scripts')
    <script>
        'use strict';

        $('.summernote').summernote({
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });

        $(document).on('submit', '#editNoticeBoardForm', function(event) {
            event.preventDefault();
            processingBtn('#editNoticeBoardForm', '#btnSave', 'loading');
            let id = $('#notice_board_id').val();
            let formData = new FormData(this);

            // Add _method to FormData
            formData.append('_method', 'PUT');

            $.ajax({
                url: route('notice-boards.update', id),
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
                    processingBtn('#editNoticeBoardForm', '#btnSave');
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
