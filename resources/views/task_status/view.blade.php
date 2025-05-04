@extends('layouts.app')
@section('title')
    {{ __('messages.task-status.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.task-status.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('task-status.index') }}"
                    class="btn btn-primary form-btn">{{ __('messages.task-status.list') }} </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">

                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">

                        <div class=" col-md-4 col-sm-12">
                            <strong>{{ __('messages.branches.name') }}</strong>
                            <p>
                                {{ $task->branch?->name ?? '' }}
                            </p>
                        </div>
                        {{-- @if (auth()->user()->is_admin) --}}
                        <div class=" col-md-4 col-sm-12">
                            <strong>{{ __('messages.task-status.user') }}</strong>
                            <p>
                                {{ $task->user?->name ?? '' }}
                            </p>
                        </div>
                        {{-- @endif --}}
                        <div class=" col-md-4 col-sm-12">
                            <strong>{{ __('messages.task-status.date') }}</strong>
                            <p>{{ \Carbon\Carbon::parse($task->date)->format('d-m-Y') }}</p>
                        </div>
                        <div class=" col-md-4 col-sm-12">
                            <strong>{{ __('messages.task-status.duration') }}</strong>
                            <p>{{ $task->duration }}</p>
                        </div>
                        <div class=" col-md-4 col-sm-12">
                            <strong>{{ __('messages.task-status.customer') }}</strong>
                            <p> {{ $task->customer->company_name ?? null }} </p>
                        </div>
                        <!-- Project Selection -->
                        <div class=" col-md-4 col-sm-12">
                            <strong>{{ __('messages.task-status.project') }}</strong>
                            <p> {{ $task->project->project_name ?? null }} </p>
                        </div>
                    </div>
                    <div class="row">
                        <!-- User Selection -->
                        <div class="col-md-4 col-sm-12 mb-2">
                            <strong>{{ __('messages.task-status.task') }}</strong>
                            <div style="color: #555;">
                                {!! nl2br(e($task->task)) !!}
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-0">
                            <strong>{{ __('messages.task-status.description') }}</strong>
                            <div style="color: #555;"> {!! $task->description !!}
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

    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection
@section('scripts')
@endsection
