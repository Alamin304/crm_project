@extends('layouts.app')

@section('title')
    {{ __('messages.membership_rules.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.membership_rules.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('membership-rules.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.membership_rules.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('name', __('messages.membership_rules.name')) }}
                            <p>{{ $membershipRule->name }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('customer_group', __('messages.membership_rules.customer_group')) }}
                            <p>{{ $membershipRule->customer_group }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('customer', __('messages.membership_rules.customer')) }}
                            <p>{{ $membershipRule->customer }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('card', __('messages.membership_rules.card')) }}
                            <p>{{ $membershipRule->card }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('point_from', __('messages.membership_rules.point_from')) }}
                            <p>{{ $membershipRule->point_from }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('point_to', __('messages.membership_rules.point_to')) }}
                            <p>{{ $membershipRule->point_to }}</p>
                        </div>
                        <div class="form-group col-sm-12 mb-0">
                            {{ Form::label('description', __('messages.membership_rules.description')) }}
                            {!! $membershipRule->description !!}
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
