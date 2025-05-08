@extends('layouts.app')

@section('title')
    {{ __('messages.booking_sources.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.booking_sources.edit') }}</h1>
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
                        {{ Form::open(['id' => 'editBookingSourceForm']) }}
                        {{ Form::hidden('id', $bookingSource->id, ['id' => 'booking_source_id']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('booking_type', __('messages.booking_sources.booking_type') . ':') }}<span class="required">*</span>
                                    {{ Form::select(
                                        'booking_type',
                                        [
                                            'Online' => 'Online',
                                            'Offline' => 'Offline',
                                            'Agent' => 'Agent',
                                            'Walk-in' => 'Walk-in',
                                            'Corporate' => 'Corporate',
                                        ],
                                        $bookingSource->booking_type,
                                        ['class' => 'form-control select2', 'required', 'id' => 'editBookingType']
                                    ) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('booking_source', __('messages.booking_sources.booking_source') . ':') }}<span class="required">*</span>
                                    {{ Form::text('booking_source', $bookingSource->booking_source, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('commission_rate', __('messages.booking_sources.commission_rate') . ' (%)' . ':') }}<span class="required">*</span>
                                    {{ Form::number('commission_rate', $bookingSource->commission_rate, [
                                        'class' => 'form-control',
                                        'required',
                                        'autocomplete' => 'off',
                                        'step' => '0.01',
                                        'min' => '0',
                                        'max' => '100'
                                    ]) }}
                                </div>
                            </div>

                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
                                    'id' => 'btnSave',
                                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."
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
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        'use strict';

        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: "{{ __('messages.booking_sources.select_booking_type') }}"
            });
        });

        $(document).on('submit', '#editBookingSourceForm', function (event) {
            event.preventDefault();
            processingBtn('#editBookingSourceForm', '#btnSave', 'loading');
            let id = $('#booking_source_id').val();

            $.ajax({
                url: route('booking-sources.update', id),
                type: 'PUT',
                data: $(this).serialize(),
                success: function (result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('booking-sources.index') }}";
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        let errors = result.responseJSON.errors;
                        let errorHtml = '<ul>';
                        $.each(errors, function (key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                        errorHtml += '</ul>';
                        $('#validationErrorsBox').html(errorHtml).removeClass('d-none');
                    }
                },
                complete: function () {
                    processingBtn('#editBookingSourceForm', '#btnSave');
                }
            });
        });
    </script>
@endsection
