@extends('layouts.app')
@section('title')
    {{ __('messages.check_in.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.check_in.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('check_ins.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.check_in.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_number', __('messages.check_in.booking_number')) }}
                            <p>{{ $checkIn->booking_number }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('room_type', __('messages.check_in.room_type')) }}
                            <p>{{ $checkIn->room_type }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('room_no', __('messages.check_in.room_no')) }}
                            <p>{{ $checkIn->room_no }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('check_in', __('messages.check_in.check_in')) }}
                            <p>{{ \Carbon\Carbon::parse($checkIn->check_in)->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('check_out', __('messages.check_in.check_out')) }}
                            <p>{{ \Carbon\Carbon::parse($checkIn->check_out)->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_status', __('messages.check_in.booking_status')) }}
                            <p>{{ $checkIn->booking_status ? 'Confirmed' : 'Pending' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('adults', __('messages.check_in.adults')) }}
                            <p>{{ $checkIn->adults }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('children', __('messages.check_in.children')) }}
                            <p>{{ $checkIn->children }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_type', __('messages.check_in.booking_type')) }}
                            <p>{{ $checkIn->booking_type }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('arrival_from', __('messages.check_in.arrival_from')) }}
                            <p>{{ $checkIn->arrival_from }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_reference', __('messages.check_in.booking_reference')) }}
                            <p>{{ $checkIn->booking_reference }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_reference_no', __('messages.check_in.booking_reference_no')) }}
                            <p>{{ $checkIn->booking_reference_no }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('visit_purpose', __('messages.check_in.visit_purpose')) }}
                            <p>{{ $checkIn->visit_purpose }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('remarks', __('messages.check_in.remarks')) }}
                            <p>{{ $checkIn->remarks }}</p>
                        </div>
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
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
@endsection
