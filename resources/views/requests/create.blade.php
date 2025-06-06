@extends('layouts.app')
@section('title')
    {{ __('messages.requests.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.requests.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('requests.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.requests.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormRequest']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('title', __('messages.requests.title') . ':') }}<span class="required">*</span>
                                    {{ Form::text('title', null, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('assets', __('messages.requests.assets') . ':') }}<span class="required">*</span>
                                    {{ Form::select('assets', $assets, null, ['class' => 'form-control select2', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('checkout_for', __('messages.requests.checkout_for') . ':') }}<span class="required">*</span>
                                    {{ Form::text('checkout_for', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('note', __('messages.requests.note') . ':') }}
                                    {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple', 'id' => 'requestNote']) }}
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
        let requestCreateUrl = "{{ route('requests.store') }}";

        $(document).ready(function () {
            $('.select2').select2();

            $('#requestNote').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('#addNewFormRequest').on('submit', function (e) {
                e.preventDefault();
                processingBtn('#addNewFormRequest', '#btnSave', 'loading');

                let noteContent = $('#requestNote').summernote('code');
                let textOnly = $('<div>').html(noteContent).text().trim();

                if (!textOnly) {
                    displayErrorMessage('Note field must not be empty or whitespace only.');
                    processingBtn('#addNewFormRequest', '#btnSave', 'reset');
                    return false;
                }

                $('#requestNote').val(textOnly);

                $.ajax({
                    url: requestCreateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('requests.index') }}";
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#addNewFormRequest', '#btnSave');
                    }
                });
            });
        });
    </script>
@endsection
