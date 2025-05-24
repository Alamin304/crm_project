@extends('layouts.app')

@section('title')
    {{ __('messages.reservations.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ __('Edit Reservation') }}</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('reservations.index') }}" class="btn btn-primary form-btn">
                {{ __('messages.reservations.list') }}
            </a>
        </div>
    </div>

    <div class="section-body">
        {{ Form::model($reservation, ['route' => ['reservations.update', $reservation->id], 'method' => 'put', 'id' => 'editReservationForm']) }}
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header"><h4>{{ __('messages.reservations.details') }}</h4></div>
                    <div class="card-body">
                        <div class="form-row">
                            {{-- Customer Name --}}
                            <div class="form-group col-md-4">
                                {{ Form::label('customer_name', 'Customer Name:') }}<span class="required">*</span>
                                {{ Form::text('customer_name', null, ['class' => 'form-control', 'required']) }}
                            </div>

                            {{-- Table Number --}}
                            <div class="form-group col-md-4">
                                {{ Form::label('table_no', 'Table No:') }}<span class="required">*</span>
                                {{ Form::text('table_no', null, ['class' => 'form-control', 'required']) }}
                            </div>

                            {{-- Number of People --}}
                            <div class="form-group col-md-4">
                                {{ Form::label('number_of_people', 'No. of People:') }}<span class="required">*</span>
                                {{ Form::number('number_of_people', null, ['class' => 'form-control', 'min' => 1, 'required']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            {{-- Date --}}
                            <div class="form-group col-md-4">
                                {{ Form::label('date', 'Reservation Date:') }}<span class="required">*</span>
                                <div class="input-group date" id="reservationDatePicker" data-target-input="nearest">
                                    {{ Form::text('date', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#reservationDatePicker', 'required']) }}
                                    <div class="input-group-append" data-target="#reservationDatePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Start Time --}}
                            <div class="form-group col-md-4">
                                {{ Form::label('start_time', 'Start Time:') }}<span class="required">*</span>
                                <div class="input-group date" id="startTimePicker" data-target-input="nearest">
                                    {{ Form::text('start_time', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#startTimePicker', 'required']) }}
                                    <div class="input-group-append" data-target="#startTimePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>

                            {{-- End Time --}}
                            <div class="form-group col-md-4">
                                {{ Form::label('end_time', 'End Time:') }}<span class="required">*</span>
                                <div class="input-group date" id="endTimePicker" data-target-input="nearest">
                                    {{ Form::text('end_time', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#endTimePicker', 'required']) }}
                                    <div class="input-group-append" data-target="#endTimePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            {{-- Status --}}
                            <div class="form-group col-md-4">
                                {{ Form::label('status', 'Status:') }}
                                {{ Form::select('status', ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'], null, ['class' => 'form-control select2']) }}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-right mt-4">
                    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary', 'id' => 'btnUpdate']) }}
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

        $('#reservationDatePicker').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: moment().startOf('day')
        });

        $('#startTimePicker, #endTimePicker').datetimepicker({
            format: 'HH:mm'
        });

        $('#editReservationForm').on('submit', function (e) {
            e.preventDefault();
            processingBtn('#editReservationForm', '#btnUpdate', 'loading');

            $.ajax({
                url: $(this).attr('action'),
                method: 'PUT',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        displaySuccessMessage(response.message);
                        window.location.href = "{{ route('reservations.index') }}";
                    }
                },
                error: function (xhr) {
                    displayErrorMessage(xhr.responseJSON.message);
                },
                complete: function () {
                    processingBtn('#editReservationForm', '#btnUpdate');
                }
            });
        });
    });
</script>
@endsection
