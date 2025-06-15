@extends('layouts.app')
@section('title')
    {{ __('messages.manufacturing_orders.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.manufacturing_orders.edit') }}</h1>
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
                        {{ Form::model($manufacturingOrder, ['id' => 'editFormManufacturingOrder', 'route' => ['manufacturing-orders.update', $manufacturingOrder->id], 'method' => 'put','enctype' => 'multipart/form-data']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('product', __('messages.manufacturing_orders.product').':') }}<span class="required">*</span>
                                    {{ Form::text('product', null, ['class' => 'form-control', 'required', 'id' => 'editMoProduct']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('quantity', __('messages.manufacturing_orders.quantity').':') }}<span class="required">*</span>
                                    {{ Form::number('quantity', null, ['class' => 'form-control', 'required', 'min' => 1, 'id' => 'editMoQuantity']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('deadline', __('messages.manufacturing_orders.deadline').':') }}<span class="required">*</span>
                                    <div class="input-group date" id="editMoDeadline" data-target-input="nearest">
                                        {{ Form::text('deadline', null, [
                                            'class' => 'form-control datetimepicker-input',
                                            'data-target' => '#editMoDeadline',
                                            'required',
                                            'placeholder' => __('messages.manufacturing_orders.deadline')
                                        ]) }}
                                        <div class="input-group-append" data-target="#editMoDeadline" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('plan_from', __('messages.manufacturing_orders.plan_from').':') }}<span class="required">*</span>
                                    <div class="input-group date" id="editMoPlanFrom" data-target-input="nearest">
                                        {{ Form::text('plan_from', null, [
                                            'class' => 'form-control datetimepicker-input',
                                            'data-target' => '#editMoPlanFrom',
                                            'required',
                                            'placeholder' => __('messages.manufacturing_orders.plan_from')
                                        ]) }}
                                        <div class="input-group-append" data-target="#editMoPlanFrom" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('unit_of_measure', __('messages.manufacturing_orders.unit_of_measure').':') }}<span class="required">*</span>
                                    {{ Form::select('unit_of_measure', $units, null, ['class' => 'form-control select2', 'required', 'id' => 'editMoUnitOfMeasure']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('responsible', __('messages.manufacturing_orders.responsible').':') }}<span class="required">*</span>
                                    {{ Form::select('responsible', array_combine($responsibles, $responsibles), null, ['class' => 'form-control select2', 'required', 'id' => 'editMoResponsible']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('bom_code', __('messages.manufacturing_orders.bom_code').':') }}<span class="required">*</span>
                                    {{ Form::select('bom_code', $boms, null, ['class' => 'form-control select2', 'required', 'id' => 'editMoBomCode']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('reference_code', __('messages.manufacturing_orders.reference_code').':') }}
                                    {{ Form::text('reference_code', null, ['class' => 'form-control', 'id' => 'editMoReferenceCode']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('routing', __('messages.manufacturing_orders.routing').':') }}<span class="required">*</span>
                                    {{ Form::select('routing', $routings, null, ['class' => 'form-control select2', 'required', 'id' => 'editMoRouting']) }}
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
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js"></script>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
        // Initialize datetimepicker
        $('#editMoDeadline, #editMoPlanFrom').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true
        });

        // Initialize select2
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select an option',
            allowClear: true
        });
    });

    // Form submission handler
    $(document).on('submit', '#editFormManufacturingOrder', function(event) {
        event.preventDefault();

        let form = $(this);
        let formData = new FormData(form[0]);
        formData.append('_method', 'PUT'); // For Laravel's PUT method

        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('#validationErrorsBox').addClass('d-none').html('');

        processingBtn(form, '#btnEditSave', 'loading');

        $.ajax({
            url: form.attr('action'),
            type: 'POST', // Must be POST when using FormData
            data: formData,
            processData: false, // Important for FormData
            contentType: false, // Important for FormData
            success: function(response) {
                if (response.success) {
                    displaySuccessMessage(response.message);
                    window.location.href = "{{ route('manufacturing-orders.index') }}";
                }
            },
            error: function(xhr) {
                let response = xhr.responseJSON;
                if (response.errors) {
                    let errors = response.errors;
                    let errorHtml = '<ul>';

                    for (let field in errors) {
                        // Add error message to list
                        errorHtml += `<li>${errors[field][0]}</li>`;
                        // Highlight the problematic field
                        $(`[name="${field}"]`).addClass('is-invalid')
                            .after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    }

                    errorHtml += '</ul>';
                    $('#validationErrorsBox').removeClass('d-none').html(errorHtml);
                } else {
                    displayErrorMessage(response.message || 'An error occurred');
                }
            },
            complete: function() {
                processingBtn(form, '#btnEditSave');
            }
        });
    });
</script>
@endsection

