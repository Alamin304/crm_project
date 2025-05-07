@extends('layouts.app')
@section('title')
    {{ __('messages.check_in.add_check_in') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ __('messages.check_in.add') }}</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('booking_lists.index') }}" class="btn btn-primary form-btn">{{ __('messages.check_in.list') }}</a>
        </div>
    </div>

    <div class="section-body">
        {{ Form::open(['id' => 'addCheckInForm']) }}
        <div class="row">
            <div class="col-md-12">
                <!-- Reservation Details -->
                <div class="card mb-4">
                    <div class="card-header"><h4>{{ __('messages.check_in.reservation_details') }}</h4></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                {{ Form::label('booking_no', 'Booking No:') }}<span class="required">*</span>
                                <div class="input-group">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-hashtag"></i></div></div>
                                    {{ Form::text('booking_no', 'BK-001', ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('check_in', 'Check In:') }}<span class="required">*</span>
                                <div class="input-group date" id="checkInPicker" data-target-input="nearest">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-calendar-check"></i></div></div>
                                    {{ Form::text('check_in', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#checkInPicker', 'required', 'autocomplete' => 'off']) }}
                                    <div class="input-group-append" data-target="#checkInPicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('check_out', 'Check Out:') }}<span class="required">*</span>
                                <div class="input-group date" id="checkOutPicker" data-target-input="nearest">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-calendar-times"></i></div></div>
                                    {{ Form::text('check_out', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#checkOutPicker', 'required', 'autocomplete' => 'off']) }}
                                    <div class="input-group-append" data-target="#checkOutPicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('arrival_from', 'Arrival From:') }}
                                <div class="input-group">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-map-marker"></i></div></div>
                                    {{ Form::text('arrival_from', 'City Name', ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                {{ Form::label('booking_type', 'Booking Type:') }}
                                {{ Form::select('booking_type', ['Online' => 'Online', 'Phone' => 'Phone', 'Walk-in' => 'Walk-in'], 'Online', ['class' => 'form-control select2', 'placeholder' => 'Select Type']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('booking_reference', 'Booking Reference:') }}
                                {{ Form::select('booking_reference', ['Ref1' => 'Ref1', 'Ref2' => 'Ref2'], null, ['class' => 'form-control select2', 'placeholder' => 'Choose Reference']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('booking_reference_no', 'Booking Ref No:') }}
                                {{ Form::select('booking_reference_no', ['001' => '001', '002' => '002'], null, ['class' => 'form-control select2', 'placeholder' => 'Choose Ref No']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('visit_purpose', 'Purpose of Visit:') }}
                                <div class="input-group">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-briefcase"></i></div></div>
                                    {{ Form::text('visit_purpose', 'Business', ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('remarks', 'Remarks:') }}
                            <div class="input-group">
                                <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-comment"></i></div></div>
                                {{ Form::text('remarks', 'No special requests', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Details -->
                <div class="card">
                    <div class="card-header"><h4>{{ __('messages.check_in.room_details') }}</h4></div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                {{ Form::label('room_type', 'Room Type:') }}
                                {{ Form::select('room_type', ['Deluxe' => 'Deluxe', 'Standard' => 'Standard'], null, ['class' => 'form-control select2', 'placeholder' => 'Select Room Type', 'id' => 'roomType']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('room_no', 'Room No:') }}
                                {{ Form::select('room_no', ['101' => '101', '102' => '102'], null, ['class' => 'form-control select2', 'placeholder' => 'Select Room No', 'id' => 'roomNo', 'disabled']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('adults', 'Adults:') }}
                                {{ Form::number('adults', 0, ['class' => 'form-control', 'min' => 0, 'id' => 'adults', 'disabled']) }}
                            </div>
                            <div class="form-group col-md-3">
                                {{ Form::label('children', 'Children:') }}
                                {{ Form::number('children', 0, ['class' => 'form-control', 'min' => 0, 'id' => 'children', 'disabled']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-4">
                    {{ Form::submit(__('Submit'), ['class' => 'btn btn-primary', 'id' => 'btnSave']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</section>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('.select2').select2({ width: '100%', allowClear: true });

    $('#checkInPicker, #checkOutPicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        sideBySide: true,
        minDate: moment()
    });

    $('#checkOutPicker').on('change.datetimepicker', function (e) {
        const checkInDate = moment($('#checkInPicker input').val());
        const checkOutDate = moment(e.date);
        if (checkOutDate.isBefore(checkInDate)) {
            alert('Check-out date/time must be after check-in.');
            $('#checkOutPicker input').val('');
        }
    });

    $('#roomType').on('change', function () {
        const enabled = $(this).val() !== '';
        $('#roomNo, #adults, #children').prop('disabled', !enabled);
    });

    $('#addCheckInForm').on('submit', function (e) {
        e.preventDefault();
        processingBtn('#addCheckInForm', '#btnSave', 'loading');

        $.ajax({
            url: "{{ route('check_ins.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    displaySuccessMessage(response.message);
                    window.location.href = "{{ route('check_ins.index') }}";
                }
            },
            error: function (xhr) {
                displayErrorMessage(xhr.responseJSON.message);
            },
            complete: function () {
                processingBtn('#addCheckInForm', '#btnSave');
            }
        });
    });
});
</script>
@endsection
