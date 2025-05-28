@extends('layouts.app')
@section('title')
    {{ __('messages.warranties.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.warranties.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('warranties.index') }}" class="btn btn-primary form-btn">{{ __('messages.warranties.list') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('claim_code', __('messages.warranties.claim_code')) }}
                            <p>{{ $warranty->claim_code }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('customer', __('messages.warranties.customer')) }}
                            <p>{{ $warranty->customer }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('invoice', __('messages.warranties.invoice')) }}
                            <p>{{ $warranty->invoice ?? '-' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('product_service_name', __('messages.warranties.product_service_name')) }}
                            <p>{{ $warranty->product_service_name }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('warranty_receipt_process', __('messages.warranties.warranty_receipt_process')) }}
                            <p>{{ $warranty->warranty_receipt_process ?? '-' }}</p>
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('status', __('messages.warranties.status')) }}
                            {{-- <p class="badge badge-{{ getWarrantyStatusClass($warranty->status) }}">{{ ucfirst($warranty->status) }}</p> --}}
                        </div>
                        <div class="form-group col-sm-12 col-md-4">
                            {{ Form::label('date_created', __('messages.warranties.date_created')) }}
                            <p>{{ $warranty->date_created ? \Carbon\Carbon::parse($warranty->date_created)->format('Y-m-d H:i:s') : '-' }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('description', __('messages.warranties.description')) }}
                            <p>{{ strip_tags($warranty->description) }}</p>
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('client_note', __('messages.warranties.client_note')) }}
                            <p>{{ strip_tags($warranty->client_note) }}</p>
                        </div>
                        <div class="form-group col-sm-12 mb-0">
                            {{ Form::label('admin_note', __('messages.warranties.admin_note')) }}
                            <p>{{ strip_tags($warranty->admin_note) }}</p>
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
