@extends('layouts.app')
@section('title')
    {{ __('messages.job_posts.view_job_post') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.job_posts.view_job_post') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right">
                <a href="{{ route('job-posts.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.job_posts.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('company_name', __('messages.job_posts.company')) }}
                            <p>{{ $jobPost->company_name }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('job_title', __('messages.job_posts.job_title')) }}
                            <p>{{ $jobPost->job_title }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('job_category', __('messages.job_posts.job_category')) }}
                            <p>{{ $jobPost->category->name ?? '-' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('job_type', __('messages.job_posts.job_type')) }}
                            <p>{{ __('messages.job_posts.'.$jobPost->job_type) }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('no_of_vacancy', __('messages.job_posts.no_of_vacancy')) }}
                            <p>{{ $jobPost->no_of_vacancy }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('date_of_closing', __('messages.job_posts.date_of_closing')) }}
                            <p>{{ \Carbon\Carbon::parse($jobPost->date_of_closing)->format('d M, Y') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('gender', __('messages.job_posts.gender')) }}
                            <p>{{ __('messages.job_posts.'.$jobPost->gender) }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('minimum_experience', __('messages.job_posts.minimum_experience')) }}
                            <p>{{ $jobPost->minimum_experience }} {{ __('messages.job_posts.years') }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('is_featured', __('messages.job_posts.is_featured')) }}
                            <p>
                                @if($jobPost->is_featured)
                                    <span class="badge badge-info">{{ __('messages.job_posts.yes') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('messages.job_posts.no') }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('status', __('messages.job_posts.status')) }}
                            <p>
                                @if($jobPost->status)
                                    <span class="badge badge-success">{{ __('messages.job_posts.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('messages.job_posts.inactive') }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('short_description', __('messages.job_posts.short_description')) }}
                            <p>{{ $jobPost->short_description }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('long_description', __('messages.job_posts.long_description')) }}
                            {!! $jobPost->long_description !!}
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
