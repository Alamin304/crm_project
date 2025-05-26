@extends('layouts.app')

@section('title')
    {{ __('messages.positions.add_positions') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.positions.add_positions') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('positions.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.positions.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormPosition']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.positions.name') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'position_name', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('details', __('messages.positions.details') . ':') }}
                                    {{ Form::textarea('details', null, ['class' => 'form-control summernote-simple', 'id' => 'positionDetails']) }}
                                </div>
                                <div class="form-group col-sm-12 mt-3">
                                    {{ Form::label('status', 'Is Active:') }}<span class="required">*</span>
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
        let positionCreateUrl = "{{ route('positions.store') }}";

        $(document).on('submit', '#addNewFormPosition', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormPosition', '#btnSave', 'loading');

            let description = $('<div />').html($('#positionDetails').summernote('code'));
            let plainText = description.text().trim();

            if (!plainText) {
                displayErrorMessage('Details field must not be empty or whitespace.');
                processingBtn('#addNewFormPosition', '#btnSave', 'reset');
                return false;
            }

            let formData = {
                name: $('#position_name').val(),
                status: $('input[name="status"]:checked').val(),
                details: plainText, // ← store plain text only
                _token: $('input[name="_token"]').val(),
            };

            $.ajax({
                url: positionCreateUrl,
                type: 'POST',
                data: formData,
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('positions.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addNewFormPosition', '#btnSave');
                },
            });
        });


        $(document).ready(function() {
            $('#positionDetails').summernote({
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
