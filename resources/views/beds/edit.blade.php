@extends('layouts.app')
@section('title')
    {{ __('messages.beds.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.beds.edit') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('beds.index') }}" class="btn btn-primary form-btn">{{ __('messages.beds.list') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'editForm']) }}
                        {{ Form::hidden('id', $bed->id, ['id' => 'bed_id']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.beds.name') . ':') }}<span class="required">*</span>
                                    {{ Form::text('name', $bed->name, ['class' => 'form-control', 'required', 'id' => 'bed_name', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.beds.description') . ':') }}
                                    {{ Form::textarea('description', $bed->description, ['class' => 'form-control summernote-simple', 'id' => 'editBedDescription']) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
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
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        'use strict';

        $(document).on('submit', '#editForm', function(event) {
            event.preventDefault();
            processingBtn('#editForm', '#btnSave', 'loading');
            let id = $('#bed_id').val();

            let description = $('<div />').html($('#editBedDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#editBedDescription').summernote('isEmpty')) {
                $('#editBedDescription').val('');
            } else if (empty) {
                displayErrorMessage('Description field must not contain only white space.');
                processingBtn('#editForm', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: route('beds.update', id),
                type: 'put',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('beds.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#editForm', '#btnSave');
                },
            });
        });

        $(document).ready(function() {
            $('#editBedDescription').summernote({
                height: 150,
                toolbar: [['style', ['bold', 'italic', 'underline', 'clear']],
                          ['para', ['ul', 'ol', 'paragraph']],
                          ['insert', ['link']], ['view', ['codeview']]]
            });
        });
    </script>
@endsection
