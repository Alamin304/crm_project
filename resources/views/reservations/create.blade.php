@extends('layouts.app')

@section('title')
    {{ __('messages.reservations.add_reservation') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ __('messages.reservations.add') }}</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('reservations.index') }}" class="btn btn-primary form-btn">{{ __('messages.reservations.list') }}</a>
        </div>
    </div>

    <div class="section-body">
        {{ Form::open(['id' => 'addReservationForm']) }}
        <div class="row">
            <div class="col-md-12">
                <!-- Reservation Details -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                {{ Form::label('customer_name', 'Customer Name:') }}<span class="required">*</span>
                                <div class="input-group">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-user"></i></div></div>
                                    {{ Form::text('customer_name', null, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('table_no', 'Table No:') }}<span class="required">*</span>
                                <div class="input-group">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-chair"></i></div></div>
                                    {{ Form::text('table_no', null, ['class' => 'form-control', 'required']) }}
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('number_of_people', 'No. of People:') }}<span class="required">*</span>
                                {{ Form::number('number_of_people', 1, ['class' => 'form-control', 'required', 'min' => 1]) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                {{ Form::label('date', 'Date:') }}<span class="required">*</span>
                                <div class="input-group date" id="datePicker" data-target-input="nearest">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div>
                                    {{ Form::text('date', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#datePicker', 'required', 'autocomplete' => 'off']) }}
                                    <div class="input-group-append" data-target="#datePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('start_time', 'Start Time:') }}<span class="required">*</span>
                                <div class="input-group time" id="startTimePicker" data-target-input="nearest">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-clock"></i></div></div>
                                    {{ Form::text('start_time', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#startTimePicker', 'required', 'autocomplete' => 'off']) }}
                                    <div class="input-group-append" data-target="#startTimePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('end_time', 'End Time:') }}<span class="required">*</span>
                                <div class="input-group time" id="endTimePicker" data-target-input="nearest">
                                    <div class="input-group-prepend"><div class="input-group-text"><i class="fa fa-clock"></i></div></div>
                                    {{ Form::text('end_time', null, ['class' => 'form-control datetimepicker-input', 'data-target' => '#endTimePicker', 'required', 'autocomplete' => 'off']) }}
                                    <div class="input-group-append" data-target="#endTimePicker" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-clock"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            {{ Form::label('status', 'Status:') }}
                            {{ Form::select('status', ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'canceled' => 'Canceled', 'completed' => 'Completed'], 'pending', ['class' => 'form-control select2']) }}
                        </div>

                        <div class="text-right mt-4">
                            {{ Form::submit(__('Submit'), ['class' => 'btn btn-primary', 'id' => 'btnSave']) }}
                        </div>
                    </div>
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

        $('#datePicker').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: moment()
        });

        $('#startTimePicker, #endTimePicker').datetimepicker({
            format: 'HH:mm',
            stepping: 15
        });

        $('#endTimePicker').on('change.datetimepicker', function (e) {
            const start = moment($('#startTimePicker input').val(), 'HH:mm');
            const end = moment(e.date, 'HH:mm');
            if (end.isBefore(start)) {
                alert('End time must be after start time.');
                $('#endTimePicker input').val('');
            }
        });

        $('#addReservationForm').on('submit', function (e) {
            e.preventDefault();
            processingBtn('#addReservationForm', '#btnSave', 'loading');

            $.ajax({
                url: "{{ route('reservations.store') }}",
                method: 'POST',
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
                    processingBtn('#addReservationForm', '#btnSave');
                }
            });
        });
    });
</script>
@endsection
