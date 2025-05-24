@extends('layouts.app')

@section('title')
    {{ __('messages.campaigns.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.campaigns.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right">
                <a href="{{ route('campaigns.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.campaigns.list') }}
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
                            {{ Form::label('campaign_code', __('messages.campaigns.campaign_code')) }}
                            <p>{{ $campaign->campaign_code }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('campaign_name', __('messages.campaigns.campaign_name')) }}
                            <p>{{ $campaign->campaign_name }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('recruitment_plan', __('messages.campaigns.recruitment_plan')) }}
                            <p>{{ $campaign->recruitment_plan }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('recruitment_channel_from', __('messages.campaigns.recruitment_channel_from')) }}
                            <p>{{ $campaign->recruitment_channel_from }}</p>
                        </div>

                        <!-- Position and Company -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('position', __('messages.campaigns.position')) }}
                            <p>{{ $campaign->position }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('company', __('messages.campaigns.company')) }}
                            <p>{{ $campaign->company }}</p>
                        </div>

                        <!-- Recruitment Details -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('recruited_quantity', __('messages.campaigns.recruited_quantity')) }}
                            <p>{{ $campaign->recruited_quantity }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('working_form', __('messages.campaigns.working_form')) }}
                            <p>{{ $campaign->working_form }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('department', __('messages.campaigns.department')) }}
                            <p>{{ $campaign->department }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('workplace', __('messages.campaigns.workplace')) }}
                            <p>{{ $campaign->workplace }}</p>
                        </div>

                        <!-- Salary Range -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('starting_salary_from', __('messages.campaigns.starting_salary_from')) }}
                            <p>{{ number_format($campaign->starting_salary_from, 2) }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('starting_salary_to', __('messages.campaigns.starting_salary_to')) }}
                            <p>{{ number_format($campaign->starting_salary_to, 2) }}</p>
                        </div>

                        <!-- Dates -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('from_date', __('messages.campaigns.from_date')) }}
                            <p>{{ \Carbon\Carbon::parse($campaign->from_date)->format('d M, Y') }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('to_date', __('messages.campaigns.to_date')) }}
                            <p>{{ \Carbon\Carbon::parse($campaign->to_date)->format('d M, Y') }}</p>
                        </div>

                        <!-- Status -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('status', __('messages.campaigns.status')) }}
                            <p>
                                @if($campaign->is_active)
                                    <span class="badge badge-success">{{ __('messages.common.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('messages.common.inactive') }}</span>
                                @endif
                            </p>
                        </div>

                        <!-- Managers and Followers -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('managers', __('messages.campaigns.managers')) }}
                            <p>
                                @if(is_array($campaign->managers) || is_object($campaign->managers))
                                    {{ implode(', ', (array)$campaign->managers) }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('followers', __('messages.campaigns.followers')) }}
                            <p>
                                @if(is_array($campaign->followers) || is_object($campaign->followers))
                                    {{ implode(', ', (array)$campaign->followers) }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <!-- Candidate Requirements -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('age_range', __('messages.campaigns.age_range')) }}
                            <p>
                                @if($campaign->age_from && $campaign->age_to)
                                    {{ $campaign->age_from }} - {{ $campaign->age_to }} years
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('gender', __('messages.campaigns.gender')) }}
                            <p>{{ ucfirst($campaign->gender) ?? 'N/A' }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('height_weight', __('messages.campaigns.height_weight')) }}
                            <p>
                                @if($campaign->height && $campaign->weight)
                                    {{ $campaign->height }}m / {{ $campaign->weight }}kg
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>

                        <!-- Qualifications -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('literacy', __('messages.campaigns.literacy')) }}
                            <p>{{ $campaign->literacy }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('seniority', __('messages.campaigns.seniority')) }}
                            <p>{{ $campaign->seniority }}</p>
                        </div>

                        <!-- SEO Information -->
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('meta_title', __('messages.campaigns.meta_title')) }}
                            <p>{{ $campaign->meta_title ?? 'N/A' }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('meta_description', __('messages.campaigns.meta_description')) }}
                            <p>{{ $campaign->meta_description ?? 'N/A' }}</p>
                        </div>

                        <!-- Attachment -->
                        @if($campaign->attachment)
                        <div class="form-group col-sm-12">
                            {{ Form::label('attachment', __('messages.campaigns.attachment')) }}
                            <div class="mt-2">
                                <a href="{{ $campaign->attachment_url }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> {{ __('messages.common.download') }}
                                </a>
                                <span class="ml-2">{{ basename($campaign->attachment) }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Reason -->
                        <div class="form-group col-sm-12">
                            {{ Form::label('reason', __('messages.campaigns.reason')) }}
                            <div class="campaign-reason">
                                {!! nl2br(e($campaign->reason)) !!}
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="form-group col-sm-12">
                            {{ Form::label('job_description', __('messages.campaigns.job_description')) }}
                            <div class="campaign-description">
                                {!! $campaign->job_description !!}
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
    .campaign-description,
    .campaign-reason {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #eee;
        margin-bottom: 20px;
    }

    .campaign-description img {
        max-width: 100%;
        height: auto;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
</style>
