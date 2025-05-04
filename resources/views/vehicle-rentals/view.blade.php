@extends('layouts.app')
@section('title')
    {{ __('messages.vehicle-rentals.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.vehicle-rentals.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('vehicle-rentals.index') }}"
                    class="btn btn-primary form-btn">{{ __('messages.common.list') }}</i>
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Rental Number</strong>
                                <p style="color: #555;">{{ $rental->rental_number ?? '' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> ID Number</strong>
                                <p style="color: #555;">{{ $rental->plate_number ?? '' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Name</strong>
                                <p style="color: #555;">{{ $rental->name ?? '' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Payment Schedule</strong>
                                <p style="color: #555;">{{ ucfirst($rental->type ?? '') }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Amount</strong>
                                <p style="color: #555;">{{ $rental->amount ?? '' }}</p>
                            </div>
                        </div>
                           <div class="col-md-6">
                            <div class="form-group">
                                <strong>Agreement Type</strong>
                                <p style="color: #555;">
                                   <p style="color: #555;">{{ $rental->agreement_type ?? '' }}</p>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Agreement Date</strong>
                                <p style="color: #555;">
                                    {{ $rental->agreement_date ? \Carbon\Carbon::parse($rental->agreement_date)->format('d-m-Y') : '' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong>Expiry Date</strong>
                                <p style="color: #555;">
                                    {{ $rental->expiry_date ? \Carbon\Carbon::parse($rental->expiry_date)->format('d-m-Y') : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong> Notification Days</strong>
                                <p style="color: #555;">{{ $rental->notification_days ?? '' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 d-none">
                            <div class="form-group">
                                <strong>Notification Date</strong>
                                <p style="color: #555;">
                                    {{ $rental->notification_date ? \Carbon\Carbon::parse($rental->notification_date)->format('d-m-Y') : '' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <strong> Description</strong>
                                <p style="color: #555;">{!! $rental->description ?? '' !!}</p>
                            </div>
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
