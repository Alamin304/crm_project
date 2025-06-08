@extends('layouts.app')
@section('title')
    {{ __('messages.location.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.location.add') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('locations.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.location.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['id' => 'addNewForm', 'files' => true]) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('location_name', __('messages.location.location_name').':') }}<span class="required">*</span>
                            {{ Form::text('location_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('parent', __('messages.location.parent').':') }}
                            {{ Form::select('parent', $parentOptions, null, ['class' => 'form-control', 'placeholder' => 'Select Parent Location']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('manager', __('messages.location.manager').':') }}
                            {{ Form::select('manager', $managerOptions, null, ['class' => 'form-control', 'placeholder' => 'Select Manager']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('location_currency', __('messages.location.location_currency').':') }}
                            {{ Form::select('location_currency', $currencyOptions, null, ['class' => 'form-control', 'placeholder' => 'Select Currency']) }}
                        </div>
                        <div class="form-group col-sm-12">
                            {{ Form::label('address', __('messages.location.address').':') }}
                            {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2]) }}
                        </div>
                        <div class="form-group col-sm-4">
                            {{ Form::label('city', __('messages.location.city').':') }}
                            {{ Form::text('city', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-4">
                            {{ Form::label('state', __('messages.location.state').':') }}
                            {{ Form::text('state', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-4">
                            {{ Form::label('country', __('messages.location.country').':') }}
                            {{ Form::text('country', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('zip_code', __('messages.location.zip_code').':') }}
                            {{ Form::text('zip_code', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('image', __('messages.location.image').':') }}
                            {{ Form::file('image', ['class' => 'form-control-file', 'accept' => 'image/*']) }}
                        </div>
                    </div>
                    <div class="text-right">
                        {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnSave']) }}
                    </div>
                    {{ Form::close() }}
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
    <script>
        $(document).ready(function() {
            $('#addNewForm').submit(function(e) {
                e.preventDefault();
                let loadingButton = $('#btnSave');
                loadingButton.attr('disabled', true);
                loadingButton.html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('locations.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('locations.index') }}";
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        loadingButton.attr('disabled', false);
                        loadingButton.html('{{ __('messages.common.save') }}');
                    }
                });
            });
        });
    </script>
@endsection
