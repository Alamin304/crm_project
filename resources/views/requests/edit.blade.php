@extends('layouts.app')
@section('title')
    {{ __('messages.requests.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.requests.edit') }}</h1>
            <div class="float-right">
                <a href="{{ route('requests.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.requests.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::model($requestModel, ['id' => 'editRequestForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('title', __('messages.requests.title') . ':') }}<span class="required">*</span>
                                    {{ Form::text('title', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('assets', __('messages.requests.assets') . ':') }}<span class="required">*</span>
                                    {{ Form::select('assets', $assets, null, ['class' => 'form-control select2', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('checkout_for', __('messages.requests.checkout_for') . ':') }}<span class="required">*</span>
                                    {{ Form::text('checkout_for', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('status', __('messages.requests.status') . ':') }}
                                    {{ Form::select('status', $statuses, null, ['class' => 'form-control select2']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('note', __('messages.requests.note') . ':') }}
                                    {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple', 'rows' => 3]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                <button type="submit"
                                        class="btn btn-primary btn-sm form-btn"
                                        id="btnEditSave"
                                        data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">
                                    {{ __('messages.common.submit') }}
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
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
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('#editRequestForm').on('submit', function (e) {
                e.preventDefault();
                $('#btnEditSave').html($('#btnEditSave').data('loading-text')).attr('disabled', true);
                $('#validationErrorsBox').addClass('d-none').html('');

                let formData = $(this).serialize();
                let updateUrl = "{{ route('requests.update', $requestModel->id) }}";

                $.ajax({
                    url: updateUrl,
                    type: 'PUT',
                    data: formData,
                    success: function (result) {
                        if (result.success) {
                            window.location.href = "{{ route('requests.index') }}";
                        }
                    },
                    error: function (result) {
                        $('#btnEditSave').html("{{ __('messages.common.submit') }}").prop('disabled', false);
                        let response = result.responseJSON;
                        if (response && response.errors) {
                            let errorMessages = '';
                            $.each(response.errors, function (key, value) {
                                errorMessages += '<li>' + value[0] + '</li>';
                            });
                            $('#validationErrorsBox').html('<ul>' + errorMessages + '</ul>').removeClass('d-none');
                        } else {
                            $('#validationErrorsBox').html(response.message).removeClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
@endsection
@extends('layouts.app')
@section('title')
    {{ __('messages.requests.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.requests.edit') }}</h1>
            <div class="float-right">
                <a href="{{ route('requests.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.requests.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::model($requestModel, ['id' => 'editRequestForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('title', __('messages.requests.title') . ':') }}<span class="required">*</span>
                                    {{ Form::text('title', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('assets', __('messages.requests.assets') . ':') }}<span class="required">*</span>
                                    {{ Form::select('assets', $assets, null, ['class' => 'form-control select2', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('checkout_for', __('messages.requests.checkout_for') . ':') }}<span class="required">*</span>
                                    {{ Form::text('checkout_for', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('status', __('messages.requests.status') . ':') }}
                                    {{ Form::select('status', $statuses, null, ['class' => 'form-control select2']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('note', __('messages.requests.note') . ':') }}
                                    {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple', 'rows' => 3]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                <button type="submit"
                                        class="btn btn-primary btn-sm form-btn"
                                        id="btnEditSave"
                                        data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">
                                    {{ __('messages.common.submit') }}
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
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
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('#editRequestForm').on('submit', function (e) {
                e.preventDefault();
                $('#btnEditSave').html($('#btnEditSave').data('loading-text')).attr('disabled', true);
                $('#validationErrorsBox').addClass('d-none').html('');

                let formData = $(this).serialize();
                let updateUrl = "{{ route('requests.update', $requestModel->id) }}";

                $.ajax({
                    url: updateUrl,
                    type: 'PUT',
                    data: formData,
                    success: function (result) {
                        if (result.success) {
                            window.location.href = "{{ route('requests.index') }}";
                        }
                    },
                    error: function (result) {
                        $('#btnEditSave').html("{{ __('messages.common.submit') }}").prop('disabled', false);
                        let response = result.responseJSON;
                        if (response && response.errors) {
                            let errorMessages = '';
                            $.each(response.errors, function (key, value) {
                                errorMessages += '<li>' + value[0] + '</li>';
                            });
                            $('#validationErrorsBox').html('<ul>' + errorMessages + '</ul>').removeClass('d-none');
                        } else {
                            $('#validationErrorsBox').html(response.message).removeClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
@endsection
@extends('layouts.app')
@section('title')
    {{ __('messages.requests.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.requests.edit') }}</h1>
            <div class="float-right">
                <a href="{{ route('requests.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.requests.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::model($requestModel, ['id' => 'editRequestForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('title', __('messages.requests.title') . ':') }}<span class="required">*</span>
                                    {{ Form::text('title', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('assets', __('messages.requests.assets') . ':') }}<span class="required">*</span>
                                    {{ Form::select('assets', $assets, null, ['class' => 'form-control select2', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('checkout_for', __('messages.requests.checkout_for') . ':') }}<span class="required">*</span>
                                    {{ Form::text('checkout_for', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('status', __('messages.requests.status') . ':') }}
                                    {{ Form::select('status', $statuses, null, ['class' => 'form-control select2']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('note', __('messages.requests.note') . ':') }}
                                    {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple', 'rows' => 3]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                <button type="submit"
                                        class="btn btn-primary btn-sm form-btn"
                                        id="btnEditSave"
                                        data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">
                                    {{ __('messages.common.submit') }}
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
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
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('#editRequestForm').on('submit', function (e) {
                e.preventDefault();
                $('#btnEditSave').html($('#btnEditSave').data('loading-text')).attr('disabled', true);
                $('#validationErrorsBox').addClass('d-none').html('');

                let formData = $(this).serialize();
                let updateUrl = "{{ route('requests.update', $requestModel->id) }}";

                $.ajax({
                    url: updateUrl,
                    type: 'PUT',
                    data: formData,
                    success: function (result) {
                        if (result.success) {
                            window.location.href = "{{ route('requests.index') }}";
                        }
                    },
                    error: function (result) {
                        $('#btnEditSave').html("{{ __('messages.common.submit') }}").prop('disabled', false);
                        let response = result.responseJSON;
                        if (response && response.errors) {
                            let errorMessages = '';
                            $.each(response.errors, function (key, value) {
                                errorMessages += '<li>' + value[0] + '</li>';
                            });
                            $('#validationErrorsBox').html('<ul>' + errorMessages + '</ul>').removeClass('d-none');
                        } else {
                            $('#validationErrorsBox').html(response.message).removeClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
@endsection
@extends('layouts.app')
@section('title')
    {{ __('messages.requests.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.requests.edit') }}</h1>
            <div class="float-right">
                <a href="{{ route('requests.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.requests.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::model($requestModel, ['id' => 'editRequestForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('title', __('messages.requests.title') . ':') }}<span class="required">*</span>
                                    {{ Form::text('title', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('assets', __('messages.requests.assets') . ':') }}<span class="required">*</span>
                                    {{ Form::select('assets', $assets, null, ['class' => 'form-control select2', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('checkout_for', __('messages.requests.checkout_for') . ':') }}<span class="required">*</span>
                                    {{ Form::text('checkout_for', null, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('status', __('messages.requests.status') . ':') }}
                                    {{ Form::select('status', $statuses, null, ['class' => 'form-control select2']) }}
                                </div>
                                <div class="form-group col-sm-12">
                                    {{ Form::label('note', __('messages.requests.note') . ':') }}
                                    {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple', 'rows' => 3]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                <button type="submit"
                                        class="btn btn-primary btn-sm form-btn"
                                        id="btnEditSave"
                                        data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">
                                    {{ __('messages.common.submit') }}
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
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
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('#editRequestForm').on('submit', function (e) {
                e.preventDefault();
                $('#btnEditSave').html($('#btnEditSave').data('loading-text')).attr('disabled', true);
                $('#validationErrorsBox').addClass('d-none').html('');

                let formData = $(this).serialize();
                let updateUrl = "{{ route('requests.update', $requestModel->id) }}";

                $.ajax({
                    url: updateUrl,
                    type: 'PUT',
                    data: formData,
                    success: function (result) {
                        if (result.success) {
                            window.location.href = "{{ route('requests.index') }}";
                        }
                    },
                    error: function (result) {
                        $('#btnEditSave').html("{{ __('messages.common.submit') }}").prop('disabled', false);
                        let response = result.responseJSON;
                        if (response && response.errors) {
                            let errorMessages = '';
                            $.each(response.errors, function (key, value) {
                                errorMessages += '<li>' + value[0] + '</li>';
                            });
                            $('#validationErrorsBox').html('<ul>' + errorMessages + '</ul>').removeClass('d-none');
                        } else {
                            $('#validationErrorsBox').html(response.message).removeClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
@endsection
