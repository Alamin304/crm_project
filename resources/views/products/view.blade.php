@extends('layouts.app')
@section('title')
    {{ __('messages.service.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.service.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('products.index') }}" class="btn btn-primary form-btn">{{ __('messages.products.list') }}</i></a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <strong> {{ Form::label('title', __('messages.service.serivce')) }}</strong>
                            <p style="color: #555;">{{ $product->title }}</p>
                        </div>
                         <div class="form-group col-md-6">
                            <strong> {{ Form::label('title', __('messages.service_categories.name')) }}</strong>
                            <p style="color: #555;">{{ $product->group->name??'' }}</p>
                        </div>
                        {{-- <div class="form-group col-sm-12 mb-0">
                            {{ Form::label('description', __('messages.common.description') . ':') }}
                            <div style="color: #555;"> {!! $product->description !!}</div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('products.templates.templates')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

