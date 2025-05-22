@extends('layouts.app')

@section('title')
    {{ __('messages.notice_boards.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.notice_boards.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right">
                <a href="{{ route('notice-boards.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.notice_boards.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('notice_type', __('messages.notice_boards.notice_type')) }}
                            <p>{{ $noticeBoard->notice_type }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('notice_by', __('messages.notice_boards.notice_by')) }}
                            <p>{{ $noticeBoard->notice_by }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('notice_date', __('messages.notice_boards.notice_date')) }}
                            <p>{{ \Carbon\Carbon::parse($noticeBoard->notice_date)->format('d M, Y') }}</p>
                        </div>

                        @if($noticeBoard->notice_attachment)
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('notice_attachment', __('messages.notice_boards.notice_attachment')) }}
                            <div class="mt-2">
                                <a href="{{ $noticeBoard->attachment_url }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Download Attachment
                                </a>
                                <span class="ml-2">{{ basename($noticeBoard->notice_attachment) }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="form-group col-sm-12">
                            {{ Form::label('description', __('messages.notice_boards.description')) }}
                            <div class="notice-description">
                                {!! $noticeBoard->description !!}
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
