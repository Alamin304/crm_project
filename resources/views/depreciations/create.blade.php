@extends('layouts.app')
@section('title')
    {{ __('messages.depreciation.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.depreciation.add') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('depreciations.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.depreciation.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['id' => 'addDepreciationForm', 'files' => true]) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('asset_name', __('messages.depreciation.asset_name').':') }}<span class="required">*</span>
                            {{ Form::select('asset_name', $assetNames, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Asset']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('serial_no', __('messages.depreciation.serial_no').':') }}
                            {{ Form::text('serial_no', null, ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('depreciation_name', __('messages.depreciation.depreciation_name').':') }}<span class="required">*</span>
                            {{ Form::text('depreciation_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('number_of_month', __('messages.depreciation.number_of_month').':') }}<span class="required">*</span>
                            {{ Form::number('number_of_month', null, ['class' => 'form-control', 'required', 'min' => 1]) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('status', __('messages.depreciation.status').':') }}<span class="required">*</span>
                            {{ Form::select('status', $statuses, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Status']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('checked_out', __('messages.depreciation.checked_out').':') }}
                            {{ Form::text('checked_out', null, ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('purchase_date', __('messages.depreciation.purchase_date').':') }}<span class="required">*</span>
                            {{ Form::text('purchase_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('EOL_date', __('messages.depreciation.EOL_date').':') }}<span class="required">*</span>
                            {{ Form::text('EOL_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('cost', __('messages.depreciation.cost').':') }}<span class="required">*</span>
                            {{ Form::text('cost', null, ['class' => 'form-control price-input', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('maintenance', __('messages.depreciation.maintenance').':') }}
                            {{ Form::text('maintenance', null, ['class' => 'form-control price-input']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('current_value', __('messages.depreciation.current_value').':') }}
                            {{ Form::text('current_value', null, ['class' => 'form-control price-input', 'readonly']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('monthly_depreciation', __('messages.depreciation.monthly_depreciation').':') }}
                            {{ Form::text('monthly_depreciation', null, ['class' => 'form-control price-input', 'readonly']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('remaining', __('messages.depreciation.remaining').':') }}
                            {{ Form::text('remaining', null, ['class' => 'form-control price-input', 'readonly']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('image', __('messages.depreciation.image').':') }}
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

            // Calculate values when cost or months change
            $('#cost, #number_of_month').on('change', function() {
                calculateDepreciation();
            });

            function calculateDepreciation() {
                const cost = parseFloat($('#cost').val()) || 0;
                const months = parseInt($('#number_of_month').val()) || 1;

                if (cost > 0 && months > 0) {
                    const monthlyDepreciation = cost / months;
                    $('#monthly_depreciation').val(monthlyDepreciation.toFixed(2));
                    $('#current_value').val(cost.toFixed(2));
                    $('#remaining').val(cost.toFixed(2));
                }
            }

            $('#addDepreciationForm').submit(function(e) {
                e.preventDefault();
                let loadingButton = $('#btnSave');
                loadingButton.attr('disabled', true);
                loadingButton.html('<span class="spinner-border spinner-border-sm"></span> Processing...');

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('depreciations.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('depreciations.index') }}";
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
