@extends('layouts.app')

@section('title')
    {{ __('messages.complementaries.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.complementaries.add_complementaries') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('complementaries.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.complementaries.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormComplementary']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('room_type', __('messages.complementaries.room_type') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::select(
                                        'room_type',
                                        [
                                            'Standard' => 'Standard',
                                            'Deluxe' => 'Deluxe',
                                            'Suite' => 'Suite',
                                            'Executive' => 'Executive',
                                            'Presidential' => 'Presidential',
                                        ],
                                        null,
                                        [
                                            'class' => 'form-control select2',
                                            'required',
                                            'id' => 'complementaryRoomType',
                                            'placeholder' => __('messages.complementaries.select_room_type'),
                                        ],
                                    ) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('complementary', __('messages.complementaries.complementary') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('complementary', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'complementaryName',
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('rate', __('messages.complementaries.rate') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::number('rate', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'complementaryRate',
                                        'autocomplete' => 'off',
                                        'step' => '0.01',
                                        'min' => '0',
                                    ]) }}
                                </div>
                            </div>
                            {{-- <div class="text-right mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-sm form-btn',
                                    'id' => 'btnSave',
                                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",
                                ]) }}
                            </div> --}}
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
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        let complementaryCreateUrl = "{{ route('complementaries.store') }}";

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "{{ __('messages.complementaries.select_room_type') }}",
            });

            // Initialize price input formatting
            $('.price-input').inputmask('decimal', {
                rightAlign: false,
                digits: 2,
                groupSeparator: ',',
                autoGroup: true,
                prefix: '',
                placeholder: '0',
                min: 0
            });
        });

        $(document).on('submit', '#addNewFormComplementary', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormComplementary', '#btnSave', 'loading');

            $.ajax({
                url: complementaryCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('complementaries.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewFormComplementary', '#btnSave');
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
