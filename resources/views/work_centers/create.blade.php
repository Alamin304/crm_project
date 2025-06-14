@extends('layouts.app')
@section('title')
    {{ __('messages.work_centers.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.work_centers.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('work-centers.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.work_centers.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormWorkCenter']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('name', __('messages.work_centers.name') . ':') }}<span class="required">*</span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'workCenterName']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('code', __('messages.work_centers.code') . ':') }}<span class="required">*</span>
                                    {{ Form::text('code', null, ['class' => 'form-control', 'required', 'id' => 'workCenterCode']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('working_hours', __('messages.work_centers.working_hours') . ':') }}<span class="required">*</span>
                                    {{ Form::select('working_hours', ['8 Hours' => '8 Hours', '12 Hours' => '12 Hours', '16 Hours' => '16 Hours', '24 Hours' => '24 Hours'], null, ['class' => 'form-control select2', 'id' => 'workingHours', 'placeholder' => 'Select Working Hours', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('time_efficiency', __('messages.work_centers.time_efficiency') . ' (%):') }}<span class="required">*</span>
                                    {{ Form::number('time_efficiency', 100, ['class' => 'form-control', 'min' => 0, 'max' => 100, 'step' => '0.01', 'required', 'id' => 'timeEfficiency']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('cost_per_hour', __('messages.work_centers.cost_per_hour') . ':') }}<span class="required">*</span>
                                    {{ Form::text('cost_per_hour', null, ['class' => 'form-control price-input', 'required', 'id' => 'costPerHour']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('capacity', __('messages.work_centers.capacity') . ':') }}<span class="required">*</span>
                                    {{ Form::number('capacity', null, ['class' => 'form-control', 'min' => 1, 'required', 'id' => 'capacity']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('oee_target', __('messages.work_centers.oee_target') . ' (%):') }}<span class="required">*</span>
                                    {{ Form::number('oee_target', 85, ['class' => 'form-control', 'min' => 0, 'max' => 100, 'step' => '0.01', 'required', 'id' => 'oeeTarget']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('time_before_prod', __('messages.work_centers.time_before_prod') . ' (minutes):') }}<span class="required">*</span>
                                    {{ Form::number('time_before_prod', 0, ['class' => 'form-control', 'min' => 0, 'required', 'id' => 'timeBeforeProd']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('time_after_prod', __('messages.work_centers.time_after_prod') . ' (minutes):') }}<span class="required">*</span>
                                    {{ Form::number('time_after_prod', 0, ['class' => 'form-control', 'min' => 0, 'required', 'id' => 'timeAfterProd']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.work_centers.description') . ':') }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'workCenterDescription', 'rows' => 3]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-sm form-btn',
                                    'id' => 'btnSave',
                                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",
                                ]) }}
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
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        let workCenterCreateUrl = "{{ route('work-centers.store') }}";

        $(document).on('submit', '#addNewFormWorkCenter', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormWorkCenter', '#btnSave', 'loading');

            let description = $('<div />').html($('#workCenterDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#workCenterDescription').summernote('isEmpty')) {
                $('#workCenterDescription').val('');
            } else if (empty) {
                displayErrorMessage('Description field must not contain only white space.');
                processingBtn('#addNewFormWorkCenter', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: workCenterCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('work-centers.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addNewFormWorkCenter', '#btnSave');
                },
            });
        });

        $(document).ready(function() {
            $('#workCenterDescription').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('.select2').select2({
                width: '100%',
            });
        });
    </script>
@endsection
