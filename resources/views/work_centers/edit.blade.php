@extends('layouts.app')
@section('title')
    {{ __('messages.work_centers.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.work_centers.edit') }}</h1>
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
                        {{ Form::model($workCenter, ['id' => 'editFormWorkCenter', 'route' => ['work-centers.update', $workCenter->id], 'method' => 'put']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('name', __('messages.work_centers.name') . ':') }}<span class="required">*</span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'editWorkCenterName']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('code', __('messages.work_centers.code') . ':') }}<span class="required">*</span>
                                    {{ Form::text('code', null, ['class' => 'form-control', 'required', 'id' => 'editWorkCenterCode']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('working_hours', __('messages.work_centers.working_hours') . ':') }}<span class="required">*</span>
                                    {{ Form::select('working_hours', ['8 Hours' => '8 Hours', '12 Hours' => '12 Hours', '16 Hours' => '16 Hours', '24 Hours' => '24 Hours'], null, ['class' => 'form-control select2', 'id' => 'editWorkingHours', 'placeholder' => 'Select Working Hours', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('time_efficiency', __('messages.work_centers.time_efficiency') . ' (%):') }}<span class="required">*</span>
                                    {{ Form::number('time_efficiency', null, ['class' => 'form-control', 'min' => 0, 'max' => 100, 'step' => '0.01', 'required', 'id' => 'editTimeEfficiency']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('cost_per_hour', __('messages.work_centers.cost_per_hour') . ':') }}<span class="required">*</span>
                                    {{ Form::text('cost_per_hour', null, ['class' => 'form-control price-input', 'required', 'id' => 'editCostPerHour']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('capacity', __('messages.work_centers.capacity') . ':') }}<span class="required">*</span>
                                    {{ Form::number('capacity', null, ['class' => 'form-control', 'min' => 1, 'required', 'id' => 'editCapacity']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('oee_target', __('messages.work_centers.oee_target') . ' (%):') }}<span class="required">*</span>
                                    {{ Form::number('oee_target', null, ['class' => 'form-control', 'min' => 0, 'max' => 100, 'step' => '0.01', 'required', 'id' => 'editOeeTarget']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('time_before_prod', __('messages.work_centers.time_before_prod') . ' (minutes):') }}<span class="required">*</span>
                                    {{ Form::number('time_before_prod', null, ['class' => 'form-control', 'min' => 0, 'required', 'id' => 'editTimeBeforeProd']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('time_after_prod', __('messages.work_centers.time_after_prod') . ' (minutes):') }}<span class="required">*</span>
                                    {{ Form::number('time_after_prod', null, ['class' => 'form-control', 'min' => 0, 'required', 'id' => 'editTimeAfterProd']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.work_centers.description') . ':') }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'editWorkCenterDescription', 'rows' => 3]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-sm form-btn',
                                    'id' => 'btnEditSave',
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
        let workCenterUrl = "{{ route('work-centers.index') }}";

        $(document).ready(function() {
            $('#editWorkCenterDescription').summernote({
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
