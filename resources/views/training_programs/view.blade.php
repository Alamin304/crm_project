@extends('layouts.app')

@section('title')
    {{ __('messages.training_programs.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.training_programs.view') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('training-programs.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.training_programs.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('program_name', __('messages.training_programs.training_name')) }}
                            <p>{{ $trainingProgram->program_name }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('training_type', __('messages.training_programs.training_type')) }}
                            <p>{{ $trainingProgram->training_type }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('program_items', __('Program Items')) }}
                            <p>{{ implode(', ', json_decode($trainingProgram->program_items, true)) }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('point', __('messages.training_programs.point')) }}
                            <p>{{ $trainingProgram->point }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('training_mode', __('Training Mode')) }}
                            <p>{{ ucfirst($trainingProgram->training_mode) }}</p>
                        </div>

                        @if($trainingProgram->staff_name)
                            <div class="form-group col-sm-12 col-md-6">
                                {{ Form::label('staff_name', __('messages.training_programs.name')) }}
                                <p>{{ $trainingProgram->staff_name }}</p>
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                                {{ Form::label('start_date', __('messages.training_programs.start_date')) }}
                                <p>{{ \Carbon\Carbon::parse($trainingProgram->start_date)->format('d M, Y') }}</p>
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                                {{ Form::label('finish_date', __('messages.training_programs.end_date')) }}
                                <p>{{ \Carbon\Carbon::parse($trainingProgram->finish_date)->format('d M, Y') }}</p>
                            </div>
                        @else
                            <div class="form-group col-sm-12 col-md-6">
                                {{ Form::label('departments', __('Departments')) }}
                                <p>{{ implode(', ', json_decode($trainingProgram->departments, true)) }}</p>
                            </div>

                            <div class="form-group col-sm-12 col-md-6">
                                {{ Form::label('apply_position', __('Apply Position')) }}
                                <p>{{ $trainingProgram->apply_position }}</p>
                            </div>
                        @endif

                        @if($trainingProgram->attachment)
                            <div class="form-group col-sm-12 col-md-6">
                                {{ Form::label('attachment', __('Attachment')) }}
                                <div class="mt-2">
                                    <a href="{{ Storage::url($trainingProgram->attachment) }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i> {{ __('Download Attachment') }}
                                    </a>
                                    <span class="ml-2">{{ basename($trainingProgram->attachment) }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="form-group col-sm-12">
                            {{ Form::label('description', __('messages.training_programs.description')) }}
                            <div class="notice-description">
                                {!! $trainingProgram->description !!}
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
    .notice-description {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 4px;
        border: 1px solid #eee;
    }

    .notice-description img {
        max-width: 100%;
        height: auto;
    }
</style>
