@extends('layouts.app')

@section('title')
    {{ __('messages.positions.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.positions.edit') }}</h1>
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
                        {{ Form::open(['id' => 'editForm']) }}
                        {{ Form::hidden('id', $position->id, ['id' => 'position_id']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.positions.name') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('name', $position->name, ['class' => 'form-control', 'required', 'id' => 'position_name', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('details', __('messages.positions.details') . ':') }}
                                    {{ Form::textarea('details', $position->details, ['class' => 'form-control summernote-simple', 'id' => 'editPositionDetails']) }}
                                </div>
                                <div class="form-group col-sm-12 mt-3">
                                    {{ Form::label('status', 'Is Active:') }}<span class="required">*</span>
                                    <div class="form-check">
                                        {{ Form::radio('status', 1, $position->status == 1, ['class' => 'form-check-input', 'id' => 'active']) }}
                                        {{ Form::label('active', 'Active', ['class' => 'form-check-label']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('status', 0, $position->status == 0, ['class' => 'form-check-input', 'id' => 'inactive']) }}
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
        let updateUrl = "{{ route('positions.update', $position->id) }}";

        $(document).on('submit', '#editForm', function(event) {
            event.preventDefault();
            processingBtn('#editForm', '#btnSave', 'loading');

            let description = $('<div />').html($('#editPositionDetails').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#editPositionDetails').summernote('isEmpty')) {
                $('#editPositionDetails').val('');
            } else if (empty) {
                displayErrorMessage('Details field must not contain only white space.');
                processingBtn('#editForm', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: updateUrl,
                type: 'PUT',
                data: $(this).serialize(),
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
                    processingBtn('#editForm', '#btnSave');
                },
            });
        });

        $(document).ready(function() {
            $('#editPositionDetails').summernote({
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
