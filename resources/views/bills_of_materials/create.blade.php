@extends('layouts.app')
@section('title')
    {{ __('messages.bills_of_materials.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.bills_of_materials.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('bills-of-materials.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.bills_of_materials.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormBom']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('product', __('messages.bills_of_materials.product') . ':') }}<span class="required">*</span>
                                    {{ Form::select('product', [
                                        'Product A' => 'Product A',
                                        'Product B' => 'Product B',
                                        'Product C' => 'Product C',
                                        'Product D' => 'Product D',
                                    ], null, ['class' => 'form-control select2', 'required', 'id' => 'product', 'placeholder' => 'Select Product']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('product_variant', __('messages.bills_of_materials.product_variant') . ':') }}
                                    {{ Form::select('product_variant', [
                                        'Variant 1' => 'Variant 1',
                                        'Variant 2' => 'Variant 2',
                                        'Variant 3' => 'Variant 3',
                                    ], null, ['class' => 'form-control select2', 'id' => 'product_variant', 'placeholder' => 'Select Variant']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('quantity', __('messages.bills_of_materials.quantity') . ':') }}<span class="required">*</span>
                                    {{ Form::number('quantity', null, ['class' => 'form-control', 'required', 'id' => 'quantity', 'step' => '0.01', 'min' => '0']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('unit_of_measure', __('messages.bills_of_materials.unit_of_measure') . ':') }}<span class="required">*</span>
                                    {{ Form::select('unit_of_measure', [
                                        'Pieces' => 'Pieces',
                                        'Kilograms' => 'Kilograms',
                                        'Liters' => 'Liters',
                                        'Meters' => 'Meters',
                                    ], null, ['class' => 'form-control select2', 'required', 'id' => 'unit_of_measure']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('routing', __('messages.bills_of_materials.routing') . ':') }}
                                    {{ Form::select('routing', [
                                        'Routing A' => 'Routing A',
                                        'Routing B' => 'Routing B',
                                        'Routing C' => 'Routing C',
                                    ], null, ['class' => 'form-control select2', 'id' => 'routing', 'placeholder' => 'Select Routing']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('bom_type', __('messages.bills_of_materials.bom_type') . ':') }}<span class="required">*</span>
                                    <div class="mt-2">
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('bom_type', 'manufacture', true, ['class' => 'form-check-input', 'id' => 'manufacture']) }}
                                            {{ Form::label('manufacture', 'Manufacture this product', ['class' => 'form-check-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline">
                                            {{ Form::radio('bom_type', 'kit', false, ['class' => 'form-check-input', 'id' => 'kit']) }}
                                            {{ Form::label('kit', 'Kit', ['class' => 'form-check-label']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('manufacturing_readiness', __('messages.bills_of_materials.manufacturing_readiness') . ':') }}
                                    {{ Form::select('manufacturing_readiness', [
                                        'Ready' => 'Ready',
                                        'Not Ready' => 'Not Ready',
                                        'In Progress' => 'In Progress',
                                    ], null, ['class' => 'form-control select2', 'id' => 'manufacturing_readiness', 'placeholder' => 'Select Status']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('consumption', __('messages.bills_of_materials.consumption') . ':') }}
                                    {{ Form::select('consumption', [
                                        'Manual' => 'Manual',
                                        'Automatic' => 'Automatic',
                                    ], null, ['class' => 'form-control select2', 'id' => 'consumption', 'placeholder' => 'Select Consumption']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.bills_of_materials.description') . ':') }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'bomDescription']) }}
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
        let bomCreateUrl = "{{ route('bills-of-materials.store') }}";

        $(document).on('submit', '#addNewFormBom', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormBom', '#btnSave', 'loading');

            let description = $('<div />').html($('#bomDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#bomDescription').summernote('isEmpty')) {
                $('#bomDescription').val('');
            } else if (empty) {
                displayErrorMessage('Description field must not contain only white space.');
                processingBtn('#addNewFormBom', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: bomCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('bills-of-materials.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addNewFormBom', '#btnSave');
                },
            });
        });

        $(document).ready(function() {
            $('#bomDescription').summernote({
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
