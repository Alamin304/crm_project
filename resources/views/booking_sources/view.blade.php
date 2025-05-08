@extends('layouts.app')

@section('title')
    {{ __('messages.booking_sources.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.booking_sources.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right">
                <a href="{{ route('booking-sources.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.booking_sources.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('booking_type', __('messages.booking_sources.booking_type')) }}
                            <p>{{ $bookingSource->booking_type }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('commission_rate', __('messages.booking_sources.commission_rate')) }}
                            <p>{{ number_format($bookingSource->commission_rate, 2) }}%</p>
                        </div>
                        <div class="form-group col-sm-12 mb-0">
                            {{ Form::label('booking_source', __('messages.booking_sources.booking_source')) }}
                            <p>{{ $bookingSource->booking_source }}</p>
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
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
@endsection
