@extends('layouts.app')
@section('title')
    {{ __('messages.routings.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.routings.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('routings.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.routings.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormRouting']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('routing_code', __('messages.routings.routing_code') . ':') }}<span class="required">*</span>
                                    {{ Form::text('routing_code', null, ['class' => 'form-control', 'required', 'id' => 'routing_code', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('routing_name', __('messages.routings.routing_name') . ':') }}<span class="required">*</span>
                                    {{ Form::text('routing_name', null, ['class' => 'form-control', 'required', 'id' => 'routing_name', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('note', __('messages.routings.note') . ':') }}
                                    {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple', 'id' => 'routingNote']) }}
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
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        let routingCreateUrl = "{{ route('routings.store') }}";

        $(document).on('submit', '#addNewFormRouting', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormRouting', '#btnSave', 'loading');

            let note = $('<div />').html($('#routingNote').summernote('code'));
            let empty = note.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#routingNote').summernote('isEmpty')) {
                $('#routingNote').val('');
            } else if (empty) {
                displayErrorMessage('Note field must not contain only white space.');
                processingBtn('#addNewFormRouting', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: routingCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('routings.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addNewFormRouting', '#btnSave');
                },
            });
        });

        $(document).ready(function() {
            $('#routingNote').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
@endsection
