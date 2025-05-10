@extends('layouts.app')

@section('title')
    {{ __('messages.complementaries.view') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.award_lists.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin"></div>
            </div>
            <div class="float-right">
                <a href="{{ route('award-lists.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.award_lists.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('award_name', __('messages.award_lists.award_name')) }}
                            <p>{{ $awardList->award_name }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('gift_item', __('messages.award_lists.gift_item')) }}
                            <p>{{ $awardList->gift_item }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('employee_name', __('messages.award_lists.employee_name')) }}
                            <p>{{ $awardList->employee_name }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('award_by', __('messages.award_lists.award_by')) }}
                            <p>{{ $awardList->award_by }}</p>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            {{ Form::label('date', __('messages.award_lists.date')) }}
                            <p>{{ \Carbon\Carbon::parse($awardList->date)->format('d M, Y') }}</p>
                        </div>

                        <div class="form-group col-sm-12">
                            {{ Form::label('award_description', __('messages.award_lists.award_description')) }}
                            <p>{!! $awardList->award_description !!}</p> {{-- Or use plain text: {{ strip_tags($awardList->award_description) }} --}}
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
