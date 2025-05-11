@extends('layouts.app')

@section('title')
    {{ __('messages.job_categories.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.job_categories.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right">
                <a href="{{ route('job-categories.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.job_categories.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Name -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('name', __('messages.job_categories.name')) }}
                            <p>{{ $jobCategory->name }}</p>
                        </div>

                        <!-- Description -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('description', __('messages.job_categories.description')) }}
                            {!! $jobCategory->description !!}
                        </div>

                        <!-- Start Date -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('start_date', __('messages.job_categories.start_date')) }}
                            <p>{{ \Carbon\Carbon::parse($jobCategory->start_date)->format('Y-m-d') }}</p>
                        </div>

                        <!-- End Date -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('end_date', __('messages.job_categories.end_date')) }}
                            <p>{{ \Carbon\Carbon::parse($jobCategory->end_date)->format('Y-m-d') }}</p>
                        </div>

                        <!-- Status -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('status', __('messages.job_categories.status')) }}
                            <p>
                                @if($jobCategory->status)
                                    <span class="badge badge-success">{{ __('messages.job_categories.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('messages.job_categories.inactive') }}</span>
                                @endif
                            </p>
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
@endsection

@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
@endsection
