@extends('layouts.app')
@section('title')
    {{ __('messages.audit.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.audit.add') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('audits.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.audit.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['id' => 'addNewForm', 'route' => 'audits.store']) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('title', __('messages.audit.title').':') }}<span class="required">*</span>
                            {{ Form::text('title', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('auditor', __('messages.audit.auditor').':') }}<span class="required">*</span>
                            {{ Form::select('auditor', $auditors, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Auditor']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('audit_date', __('messages.audit.audit_date').':') }}<span class="required">*</span>
                            {{ Form::text('audit_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('status', __('messages.audit.status').':') }}<span class="required">*</span>
                            {{ Form::select('status', $statuses, 'new', ['class' => 'form-control', 'required']) }}
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
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false,
                icons: {
                    up: 'fas fa-chevron-up',
                    down: 'fas fa-chevron-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right'
                }
            });

            $('#addNewForm').submit(function(e) {
                e.preventDefault();
                let loadingButton = $('#btnSave');
                loadingButton.attr('disabled', true);
                loadingButton.html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                $.ajax({
                    url: "{{ route('audits.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('audits.index') }}";
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
