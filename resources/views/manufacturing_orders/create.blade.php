@extends('layouts.app')
@section('title')
    {{ __('messages.manufacturing_orders.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.manufacturing_orders.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('manufacturing-orders.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.manufacturing_orders.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormManufacturingOrder']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('product', __('messages.manufacturing_orders.product').':') }}<span class="required">*</span>
                                    {{ Form::text('product', null, ['class' => 'form-control', 'required', 'id' => 'moProduct']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('quantity', __('messages.manufacturing_orders.quantity').':') }}<span class="required">*</span>
                                    {{ Form::number('quantity', null, ['class' => 'form-control', 'required', 'min' => 1, 'id' => 'moQuantity']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('deadline', __('messages.manufacturing_orders.deadline').':') }}<span class="required">*</span>
                                    <div class="input-group date" id="moDeadline" data-target-input="nearest">
                                        {{ Form::text('deadline', null, [
                                            'class' => 'form-control datetimepicker-input',
                                            'data-target' => '#moDeadline',
                                            'required',
                                            'placeholder' => __('messages.manufacturing_orders.deadline')
                                        ]) }}
                                        <div class="input-group-append" data-target="#moDeadline" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('plan_from', __('messages.manufacturing_orders.plan_from').':') }}<span class="required">*</span>
                                    <div class="input-group date" id="moPlanFrom" data-target-input="nearest">
                                        {{ Form::text('plan_from', null, [
                                            'class' => 'form-control datetimepicker-input',
                                            'data-target' => '#moPlanFrom',
                                            'required',
                                            'placeholder' => __('messages.manufacturing_orders.plan_from')
                                        ]) }}
                                        <div class="input-group-append" data-target="#moPlanFrom" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('unit_of_measure', __('messages.manufacturing_orders.unit_of_measure').':') }}<span class="required">*</span>
                                    {{ Form::select('unit_of_measure', $units, null, ['class' => 'form-control select2', 'required', 'id' => 'moUnitOfMeasure']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('responsible', __('messages.manufacturing_orders.responsible').':') }}<span class="required">*</span>
                                    {{ Form::select('responsible', array_combine($responsibles, $responsibles), null, ['class' => 'form-control select2', 'required', 'id' => 'moResponsible']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('bom_code', __('messages.manufacturing_orders.bom_code').':') }}<span class="required">*</span>
                                    {{ Form::select('bom_code', $boms, null, ['class' => 'form-control select2', 'required', 'id' => 'moBomCode']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('reference_code', __('messages.manufacturing_orders.reference_code').':') }}
                                    {{ Form::text('reference_code', null, ['class' => 'form-control', 'id' => 'moReferenceCode']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('routing', __('messages.manufacturing_orders.routing').':') }}<span class="required">*</span>
                                    {{ Form::select('routing', $routings, null, ['class' => 'form-control select2', 'required', 'id' => 'moRouting']) }}
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
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize datetimepicker
            $('#moDeadline, #moPlanFrom').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                sideBySide: true
            });

            $('.select2').select2({
                width: '100%',
                placeholder: 'Select an option',
                allowClear: true
            });
        });

        let manufacturingOrderCreateUrl = "{{ route('manufacturing-orders.store') }}";

        $(document).on('submit', '#addNewFormManufacturingOrder', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormManufacturingOrder', '#btnSave', 'loading');

            $.ajax({
                url: manufacturingOrderCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('manufacturing-orders.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addNewFormManufacturingOrder', '#btnSave');
                },
            });
        });
    </script>
@endsection
