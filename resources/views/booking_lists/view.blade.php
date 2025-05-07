@extends('layouts.app')
@section('title')
    {{ __('messages.booking_lists.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.booking_lists.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('booking_lists.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.booking_lists.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_number', __('messages.booking_lists.booking_number')) }}
                            <p>{{ $bookingList->booking_number }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('room_type', __('messages.booking_lists.room_type')) }}
                            <p>{{ $bookingList->room_type }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('room_no', __('messages.booking_lists.room_no')) }}
                            <p>{{ $bookingList->room_no }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('check_in', __('messages.booking_lists.check_in')) }}
                            <p>{{ \Carbon\Carbon::parse($bookingList->check_in)->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('check_out', __('messages.booking_lists.check_out')) }}
                            <p>{{ \Carbon\Carbon::parse($bookingList->check_out)->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_status', __('messages.booking_lists.booking_status')) }}
                            <p>{{ $bookingList->booking_status ? 'Confirmed' : 'Pending' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('adults', __('messages.booking_lists.adults')) }}
                            <p>{{ $bookingList->adults }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('children', __('messages.booking_lists.children')) }}
                            <p>{{ $bookingList->children }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_type', __('messages.booking_lists.booking_type')) }}
                            <p>{{ $bookingList->booking_type }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('arrival_from', __('messages.booking_lists.arrival_from')) }}
                            <p>{{ $bookingList->arrival_from }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_reference', __('messages.booking_lists.booking_reference')) }}
                            <p>{{ $bookingList->booking_reference }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('booking_reference_no', __('messages.booking_lists.booking_reference_no')) }}
                            <p>{{ $bookingList->booking_reference_no }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('visit_purpose', __('messages.booking_lists.visit_purpose')) }}
                            <p>{{ $bookingList->visit_purpose }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('remarks', __('messages.booking_lists.remarks')) }}
                            <p>{{ $bookingList->remarks }}</p>
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
