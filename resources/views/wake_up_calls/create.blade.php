@extends('layouts.app')
@section('title')
{{ __('messages.wake_up_calls.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.wake_up_calls.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('wake_up_calls.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.wake_up_calls.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addWakeUpCallForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('customer_name', 'Customer Name:') }}<span class="required">*</span>
                                    {{ Form::select('customer_name', [], null, ['class' => 'form-control select2', 'required', 'id' => 'customerName', 'placeholder' => 'Select Customer']) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('date', 'Date & Time:') }}<span class="required">*</span>
                                    <div class="input-group date" id="wakeUpDatePicker" data-target-input="nearest">
                                        {{ Form::text('date', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#wakeUpDatePicker', 'required', 'autocomplete' => 'off']) }}
                                        <div class="input-group-append" data-target="#wakeUpDatePicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', 'Description:') }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'wakeUpDescription']) }}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
@endsection

@section('scripts')
    <script>
        let wakeUpCreateUrl = "{{ route('wake_up_calls.store') }}";

        $(document).ready(function () {
            // Initialize Summernote
            $('#wakeUpDescription').summernote({
                height: 150,
                toolbar: [['style', ['bold', 'italic', 'underline', 'clear']],
                          ['para', ['ul', 'ol', 'paragraph']],
                          ['insert', ['link']], ['view', ['codeview']]]
            });

            // Initialize DateTime Picker
            $('#wakeUpDatePicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                sideBySide: true,
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-chevron-up',
                    down: 'fas fa-chevron-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right'
                }
            });

            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select Customer'
            });

            // Form submission
            $(document).on('submit', '#addWakeUpCallForm', function (e) {
                e.preventDefault();
                processingBtn('#addWakeUpCallForm', '#btnSave', 'loading');

                let description = $('<div />').html($('#wakeUpDescription').summernote('code'));
                let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

                if ($('#wakeUpDescription').summernote('isEmpty')) {
                    $('#wakeUpDescription').val('');
                } else if (empty) {
                    displayErrorMessage('Description must not contain only white space.');
                    processingBtn('#addWakeUpCallForm', '#btnSave', 'reset');
                    return false;
                }

                $.ajax({
                    url: wakeUpCreateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('wake_up_calls.index') }}";
                        }
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        processingBtn('#addWakeUpCallForm', '#btnSave');
                    }
                });
            });
        });
    </script>
@endsection
