@extends('layouts.app')

@section('title')
    {{ __('messages.booking_sources.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.booking_sources.add_booking_source') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('booking-sources.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.booking_sources.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormBookingSource']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('booking_type', __('messages.booking_sources.booking_type') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::select(
                                        'booking_type',
                                        [
                                            'Online' => 'Online',
                                            'Offline' => 'Offline',
                                            'Corporate' => 'Corporate',
                                            'Travel Agency' => 'Travel Agency',
                                            'Direct' => 'Direct',
                                        ],
                                        null,
                                        [
                                            'class' => 'form-control select2',
                                            'required',
                                            'id' => 'bookingSourceType',
                                            'placeholder' => __('messages.booking_sources.select_booking_type'),
                                        ],
                                    ) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('booking_source', __('messages.booking_sources.booking_source') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('booking_source', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'bookingSourceName',
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('commission_rate', __('messages.booking_sources.commission_rate') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::number('commission_rate', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'commissionRate',
                                        'autocomplete' => 'off',
                                        'step' => '0.01', 
                                        'min' => '0',
                                    ]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
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
        let bookingSourceCreateUrl = "{{ route('booking-sources.store') }}";

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "{{ __('messages.booking_sources.select_booking_type') }}",
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

        $(document).on('submit', '#addNewFormBookingSource', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormBookingSource', '#btnSave', 'loading');

            $.ajax({
                url: bookingSourceCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('booking-sources.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewFormBookingSource', '#btnSave');
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
