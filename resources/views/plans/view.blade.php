@extends('layouts.app')

@section('title')
    {{ __('messages.plans.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.plans.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right">
                <a href="{{ route('plans.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.plans.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('plan_name', __('messages.plans.plan_name')) }}
                            <p>{{ $plan->plan_name }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('position', __('messages.plans.position')) }}
                            <p>{{ $plan->position }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('department', __('messages.plans.department')) }}
                            <p>{{ $plan->department }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('recruited_quantity', __('messages.plans.recruited_quantity')) }}
                            <p>{{ $plan->recruited_quantity }}</p>
                        </div>

                        <!-- Work Details -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('working_form', __('messages.plans.working_form')) }}
                            <p>{{ $plan->working_form }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('workplace', __('messages.plans.workplace')) }}
                            <p>{{ $plan->workplace }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('starting_salary_from', __('messages.plans.starting_salary_from')) }}
                            <p>{{ number_format($plan->starting_salary_from, 2) }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('starting_salary_to', __('messages.plans.starting_salary_to')) }}
                            <p>{{ number_format($plan->starting_salary_to, 2) }}</p>
                        </div>

                        <!-- Dates -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('from_date', __('messages.plans.from_date')) }}
                            <p>{{ \Carbon\Carbon::parse($plan->from_date)->format('d M, Y') }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('to_date', __('messages.plans.to_date')) }}
                            <p>{{ \Carbon\Carbon::parse($plan->to_date)->format('d M, Y') }}</p>
                        </div>

                        <!-- Approver -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('approver', __('messages.plans.approver')) }}
                            <p>{{ $plan->approver }}</p>
                        </div>

                        <!-- Candidate Requirements -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('age_range', __('messages.plans.age_range')) }}
                            <p>
                                @if($plan->age_from && $plan->age_to)
                                    {{ $plan->age_from }} - {{ $plan->age_to }} years
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('gender', __('messages.plans.gender')) }}
                            <p>{{ ucfirst($plan->gender) ?? 'N/A' }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('height_weight', __('messages.plans.height_weight')) }}
                            <p>
                                @if($plan->height && $plan->weight)
                                    {{ $plan->height }}m / {{ $plan->weight }}kg
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <!-- Qualifications -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('literacy', __('messages.plans.literacy')) }}
                            <p>{{ $plan->literacy }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('seniority', __('messages.plans.seniority')) }}
                            <p>{{ $plan->seniority }}</p>
                        </div>

                        <!-- Attachment -->
                        @if($plan->attachment)
                        <div class="form-group col-sm-12">
                            {{ Form::label('attachment', __('messages.plans.attachment')) }}
                            <div class="mt-2">
                                <a href="{{ $plan->attachment_url }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> {{ __('messages.common.download') }}
                                </a>
                                <span class="ml-2">{{ basename($plan->attachment) }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Reason -->
                        <div class="form-group col-sm-12">
                            {{ Form::label('reason', __('messages.plans.reason')) }}
                            <div class="plan-reason">
                                {!! nl2br(e($plan->reason)) !!}
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="form-group col-sm-12">
                            {{ Form::label('job_description', __('messages.plans.job_description')) }}
                            <div class="plan-description">
                                {!! $plan->job_description !!}
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
@endsection

<style>
    .plan-description,
    .plan-reason {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #eee;
        margin-bottom: 20px;
    }

    .plan-description img {
        max-width: 100%;
        height: auto;
    }
</style>
