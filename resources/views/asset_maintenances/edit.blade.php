@extends('layouts.app')
@section('title')
    {{ __('messages.asset_maintenance.edit_asset_maintenance') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
     <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.asset_maintenance.edit_asset_maintenance') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('asset-maintenances.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.asset_maintenance.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['route' => ['asset-maintenances.update', $assetMaintenance->id], 'method' => 'put', 'id' => 'editAssetMaintenanceForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('asset_id', __('messages.asset_maintenance.asset').':') }}<span class="required">*</span>
                                    {{ Form::select('asset_id', $assets, $assetMaintenance->asset_id, ['class' => 'form-control select2', 'required', 'id' => 'editAssetId', 'placeholder' => __('messages.common.select_asset')]) }}
                                </div>
                                {{-- <div class="form-group col-sm-6">
                                    {{ Form::label('supplier_id', __('messages.asset_maintenance.supplier').':') }}<span class="required">*</span>
                                    {{ Form::select('supplier_id', $suppliers, $assetMaintenance->supplier_id, ['class' => 'form-control select2', 'required', 'id' => 'editSupplierId', 'placeholder' => __('messages.common.select_supplier')]) }}
                                </div> --}}
                                <div class="form-group col-sm-6">
                                    {{ Form::label('maintenance_type', __('messages.asset_maintenance.maintenance_type').':') }}<span class="required">*</span>
                                    {{ Form::select('maintenance_type', $maintenanceTypes, $assetMaintenance->maintenance_type, ['class' => 'form-control select2', 'required', 'id' => 'editMaintenanceType', 'placeholder' => __('messages.common.select_maintenance_type')]) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('title', __('messages.asset_maintenance.title').':') }}<span class="required">*</span>
                                    {{ Form::text('title', $assetMaintenance->title, ['class' => 'form-control', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('start_date', __('messages.asset_maintenance.start_date').':') }}<span class="required">*</span>
                                    {{ Form::text('start_date', $assetMaintenance->start_date->format('Y-m-d'), ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('completion_date', __('messages.asset_maintenance.completion_date').':') }}
                                    {{ Form::text('completion_date', $assetMaintenance->completion_date ? $assetMaintenance->completion_date->format('Y-m-d') : null, ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('warranty_improvement', __('messages.asset_maintenance.warranty_improvement').':') }}
                                    <div class="custom-switches-stacked mt-2">
                                        <label class="custom-switch">
                                            {{ Form::checkbox('warranty_improvement', 1, $assetMaintenance->warranty_improvement, ['class' => 'custom-switch-input']) }}
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">{{ __('messages.common.yes') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('cost', __('messages.asset_maintenance.cost').':') }}
                                    {{ Form::number('cost', $assetMaintenance->cost, ['class' => 'form-control', 'min' => 0, 'step' => '0.01']) }}
                                </div>
                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('notes', __('messages.asset_maintenance.notes').':') }}
                                    {{ Form::textarea('notes', $assetMaintenance->notes, ['class' => 'form-control summernote-simple', 'id' => 'editAssetMaintenanceNotes', 'rows' => 4]) }}
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
    {{-- <script src="{{ mix('assets/js/flatpickr/flatpickr.min.js') }}"></script> --}}
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        $(document).on('submit', '#editAssetMaintenanceForm', function(event) {
            event.preventDefault();
            processingBtn('#editAssetMaintenanceForm', '#btnEditSave', 'loading');

            let htmlContent = $('#editAssetMaintenanceNotes').summernote('code');
            let textContent = $('<div />').html(htmlContent).text().trim();

            if (!textContent) {
                $('#editAssetMaintenanceNotes').val('');
            } else {
                $('#editAssetMaintenanceNotes').val(textContent);
            }

            let id = "{{ $assetMaintenance->id }}";

            $.ajax({
                url: "{{ route('asset-maintenances.update', ['asset_maintenance' => $assetMaintenance->id]) }}",
                type: 'PUT',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('asset-maintenances.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#editAssetMaintenanceForm', '#btnEditSave');
                },
            });
        });

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

            $('#editAssetId, #editSupplierId, #editMaintenanceType').select2({
                width: '100%'
            });

            $('#editAssetMaintenanceNotes').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
@endsection
