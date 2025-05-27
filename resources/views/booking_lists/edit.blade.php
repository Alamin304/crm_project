@extends('layouts.app')
@section('title')
    {{ __('messages.booking_lists.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css"
        rel="stylesheet" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Edit Booking') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('booking_lists.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.booking_lists.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            {{ Form::model($bookingList, ['route' => ['booking_lists.update', $bookingList->id], 'method' => 'put', 'id' => 'editBookingForm']) }}
            <div class="row">
                <div class="col-md-12">

                    {{-- Reservation Details --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>{{ __('messages.booking_lists.reservation_details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                {{-- Check-in --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('check_in', 'Check In:') }}<span class="required">*</span>
                                    <div class="input-group date" id="checkInPicker" data-target-input="nearest">
                                        {{ Form::text('check_in', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#checkInPicker', 'required']) }}
                                        <div class="input-group-append" data-target="#checkInPicker"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Check-out --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('check_out', 'Check Out:') }}<span class="required">*</span>
                                    <div class="input-group date" id="checkOutPicker" data-target-input="nearest">
                                        {{ Form::text('check_out', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#checkOutPicker', 'required']) }}
                                        <div class="input-group-append" data-target="#checkOutPicker"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Arrival From --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('arrival_from', 'Arrival From:') }}
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fa fa-map-marker"></i></span></div>
                                        {{ Form::text('arrival_from', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>

                                {{-- Booking Type --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('booking_type', 'Booking Type:') }}
                                    {{ Form::select('booking_type', ['Online' => 'Online', 'Phone' => 'Phone', 'Walk-in' => 'Walk-in'], null, ['class' => 'form-control select2', 'placeholder' => 'Select Type']) }}
                                </div>
                            </div>

                            <div class="form-row">
                                {{-- Booking Reference --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('booking_reference', 'Booking Reference:') }}
                                    {{ Form::select('booking_reference', ['Ref1' => 'Ref1', 'Ref2' => 'Ref2'], null, ['class' => 'form-control select2']) }}
                                </div>

                                {{-- Booking Reference No --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('booking_reference_no', 'Booking Ref No:') }}
                                    {{ Form::select('booking_reference_no', ['BR001' => 'BR001', 'BR002' => 'BR002'], null, ['class' => 'form-control select2']) }}
                                </div>

                                {{-- Visit Purpose --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('visit_purpose', 'Purpose of Visit:') }}
                                    {{ Form::text('visit_purpose', null, ['class' => 'form-control']) }}
                                </div>

                                {{-- Remarks --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('remarks', 'Remarks:') }}
                                    {{ Form::text('remarks', null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Room Details --}}
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('messages.booking_lists.room_details') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                {{-- Room Type --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('room_type', 'Room Type:') }}
                                    {{ Form::select('room_type', ['Deluxe' => 'Deluxe', 'Suite' => 'Suite'], null, ['class' => 'form-control select2', 'placeholder' => 'Select Room Type', 'id' => 'roomType']) }}
                                </div>

                                {{-- Room No --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('room_no', 'Room No:') }}
                                    {{ Form::select('room_no', ['101' => '101', '102' => '102'], null, ['class' => 'form-control select2', 'id' => 'roomNo', 'disabled']) }}
                                </div>

                                {{-- Adults --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('adults', 'Adults:') }}
                                    {{ Form::number('adults', null, ['class' => 'form-control', 'min' => 0, 'id' => 'adults', 'disabled']) }}
                                </div>

                                {{-- Children --}}
                                <div class="form-group col-md-3">
                                    {{ Form::label('children', 'Children:') }}
                                    {{ Form::number('children', null, ['class' => 'form-control', 'min' => 0, 'id' => 'children', 'disabled']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-3 mr-1">
                        {{ Form::button(__('messages.common.submit'), [
                            'type' => 'submit',
                            'class' => 'btn btn-primary btn-sm form-btn',
                            'id' => 'btnUpdate',
                            'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",
                        ]) }}
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
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js">
    </script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                allowClear: true
            });

            $('#checkInPicker, #checkOutPicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                sideBySide: true,
                minDate: moment().startOf('minute')
            });

            // Enable room-related fields if room type is already selected
            if ($('#roomType').val()) {
                $('#roomNo, #adults, #children').prop('disabled', false);
            }

            // Enable room fields on room_type change
            $('#roomType').on('change', function() {
                let enable = $(this).val() !== '';
                $('#roomNo, #adults, #children').prop('disabled', !enable);
            });

            // Optional: AJAX form submit for update
            $('#editBookingForm').on('submit', function(e) {
                e.preventDefault();
                processingBtn('#editBookingForm', '#btnUpdate', 'loading');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            displaySuccessMessage(response.message);
                            window.location.href = "{{ route('booking_lists.index') }}";
                        }
                    },
                    error: function(xhr) {
                        displayErrorMessage(xhr.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#editBookingForm', '#btnUpdate');
                    }
                });
            });
        });
    </script>
@endsection
