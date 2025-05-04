@extends('layouts.app')
@section('title')
    {{ __('messages.beds.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.beds.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('beds.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.beds.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormBed']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.beds.name') . ':') }}<span class="required">*</span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'bed_name', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.beds.description') . ':') }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'bedDescription']) }}
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
        let bedCreateUrl = "{{ route('beds.store') }}";

        $(document).on('submit', '#addNewFormBed', function (event) {
            event.preventDefault();
            processingBtn('#addNewFormBed', '#btnSave', 'loading');

            let description = $('<div />').html($('#bedDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#bedDescription').summernote('isEmpty')) {
                $('#bedDescription').val('');
            } else if (empty) {
                displayErrorMessage('Description field must not contain only white space.');
                processingBtn('#addNewFormBed', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: bedCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function (result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('beds.index') }}";
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function () {
                    processingBtn('#addNewFormBed', '#btnSave');
                },
            });
        });

        $(document).ready(function () {
            $('#bedDescription').summernote({
                height: 150,
                toolbar: [['style', ['bold', 'italic', 'underline', 'clear']],
                          ['para', ['ul', 'ol', 'paragraph']],
                          ['insert', ['link']], ['view', ['codeview']]]
            });
        });
    </script>
@endsection
