@extends('layouts.app')

@section('title')
    {{ __('messages.reservations.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.reservations.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('reservations.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.reservations.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('customer_name', __('messages.reservations.customer_name')) }}
                            <p>{{ $reservation->customer_name }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('table_no', __('messages.reservations.table_no')) }}
                            <p>{{ $reservation->table_no }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('number_of_people', __('messages.reservations.number_of_people')) }}
                            <p>{{ $reservation->number_of_people }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('start_time', __('messages.reservations.start_time')) }}
                            <p>{{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('end_time', __('messages.reservations.end_time')) }}
                            <p>{{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('date', __('messages.reservations.date')) }}
                            <p>{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('status', __('messages.reservations.status')) }}
                            <p>{{ ucfirst($reservation->status) }}</p>
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
