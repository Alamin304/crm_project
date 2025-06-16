@extends('layouts.app')
@section('title')
    {{ __('messages.manufacturing_setting.manufacturing_settings') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tabs-container {
            margin-top: 20px;
        }

        .tabs-container .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        .tabs-container .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            padding: 0.5rem 1rem;
            color: #495057;
        }

        .tabs-container .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
        }

        .tabs-container .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        .tabs-container .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.25rem 0.25rem;
            background-color: #fff;
        }

        .action-btn {
            margin: 2px;
        }

        /* Add to your CSS section or file */
        .modal {
            overflow-y: auto;
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            z-index: 1050;
        }

        body.modal-open {
            overflow: auto;
            padding-right: 0 !important;
        }

        .modal-dialog {
            margin: 30px auto;
            z-index: 1060;
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.manufacturing_setting.manufacturing_settings') }}</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#working-hours" role="tab">
                                    {{ __('messages.manufacturing_setting.working_hours.title') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#unit-of-measure-categories" role="tab">
                                    {{ __('messages.manufacturing_setting.unit_of_measure_categories.title') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#unit-of-measures" role="tab">
                                    {{ __('messages.manufacturing_setting.unit_of_measures.title') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#material-codes" role="tab">
                                    {{ __('messages.manufacturing_setting.material_codes.title') }}
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Working Hours Tab -->
                            <div class="tab-pane active" id="working-hours" role="tabpanel">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>{{ __('messages.manufacturing_setting.working_hours.title') }}</h4>
                                    {{-- <button type="button" class="btn btn-primary form-btn" data-toggle="modal"
                                        data-target="#addWorkingHourModal">
                                        {{ __('messages.manufacturing_setting.working_hours.add') }}
                                    </button> --}}
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="workingHourTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.manufacturing_setting.working_hours.name') }}</th>
                                                <th>{{ __('messages.manufacturing_setting.working_hours.hours_per_day') }}
                                                </th>
                                                <th>{{ __('messages.common.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- Working Hours Add Modal -->
                                <div class="modal fade" id="addWorkingHourModal" tabindex="-1" role="dialog"
                                    aria-labelledby="addWorkingHourModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addWorkingHourModalLabel">
                                                    {{ __('messages.manufacturing_setting.working_hours.add') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="addWorkingHourForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="workingHourValidationErrorsBox"></div>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="name">{{ __('messages.manufacturing_setting.working_hours.name') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control" name="name"
                                                                required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="hours_per_day">{{ __('messages.manufacturing_setting.working_hours.hours_per_day') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="number" class="form-control" name="hours_per_day"
                                                                step="0.01" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="workingHourSaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Working Hours Edit Modal -->
                                <div class="modal fade" id="editWorkingHourModal" tabindex="-1" role="dialog"
                                    aria-labelledby="editWorkingHourModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editWorkingHourModalLabel">
                                                    {{ __('messages.manufacturing_setting.working_hours.edit') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="editWorkingHourForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="editWorkingHourValidationErrorsBox"></div>
                                                    <input type="hidden" name="working_hour_id" id="workingHourId">
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_name">{{ __('messages.manufacturing_setting.working_hours.name') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control" name="name"
                                                                id="editName" required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_hours_per_day">{{ __('messages.manufacturing_setting.working_hours.hours_per_day') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="number" class="form-control"
                                                                name="hours_per_day" id="editHoursPerDay" step="0.01"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="editWorkingHourSaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Unit of Measure Categories Tab -->
                            <div class="tab-pane" id="unit-of-measure-categories" role="tabpanel">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>{{ __('messages.manufacturing_setting.unit_of_measure_categories.title') }}</h4>
                                    {{-- <button type="button" class="btn btn-primary form-btn" data-toggle="modal"
                                        data-target="#addUnitOfMeasureCategoryModal">
                                        {{ __('messages.manufacturing_setting.unit_of_measure_categories.add') }}
                                    </button> --}}
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="unitOfMeasureCategoryTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.manufacturing_setting.unit_of_measure_categories.category_name') }}
                                                </th>
                                                <th>{{ __('messages.common.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- Unit of Measure Category Add Modal -->
                                <div class="modal fade" id="addUnitOfMeasureCategoryModal" tabindex="-1" role="dialog"
                                    aria-labelledby="addUnitOfMeasureCategoryModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addUnitOfMeasureCategoryModalLabel">
                                                    {{ __('messages.manufacturing_setting.unit_of_measure_categories.add') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="addUnitOfMeasureCategoryForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="unitOfMeasureCategoryValidationErrorsBox"></div>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="category_name">{{ __('messages.manufacturing_setting.unit_of_measure_categories.category_name') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="category_name" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="unitOfMeasureCategorySaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Unit of Measure Category Edit Modal -->
                                <div class="modal fade" id="editUnitOfMeasureCategoryModal" tabindex="-1"
                                    role="dialog" aria-labelledby="editUnitOfMeasureCategoryModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUnitOfMeasureCategoryModalLabel">
                                                    {{ __('messages.manufacturing_setting.unit_of_measure_categories.edit') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="editUnitOfMeasureCategoryForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="editUnitOfMeasureCategoryValidationErrorsBox"></div>
                                                    <input type="hidden" name="category_id" id="categoryId">
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_category_name">{{ __('messages.manufacturing_setting.unit_of_measure_categories.category_name') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="category_name" id="editCategoryName" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="editUnitOfMeasureCategorySaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Unit of Measures Tab -->
                            <div class="tab-pane" id="unit-of-measures" role="tabpanel">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>{{ __('messages.manufacturing_setting.unit_of_measures.title') }}</h4>
                                    {{-- <button type="button" class="btn btn-primary form-btn" data-toggle="modal"
                                        data-target="#addUnitOfMeasureModal">
                                        {{ __('messages.manufacturing_setting.unit_of_measures.add') }}
                                    </button> --}}
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="unitOfMeasureTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.manufacturing_setting.unit_of_measures.name') }}</th>
                                                <th>{{ __('messages.manufacturing_setting.unit_of_measures.type') }}</th>
                                                <th>{{ __('messages.manufacturing_setting.unit_of_measures.category') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.unit_of_measures.rounding_precision') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.unit_of_measures.is_active') }}
                                                </th>
                                                <th>{{ __('messages.common.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- Unit of Measure Add Modal -->
                                <div class="modal fade" id="addUnitOfMeasureModal" tabindex="-1" role="dialog"
                                    aria-labelledby="addUnitOfMeasureModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addUnitOfMeasureModalLabel">
                                                    {{ __('messages.manufacturing_setting.unit_of_measures.add') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="addUnitOfMeasureForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="unitOfMeasureValidationErrorsBox"></div>
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="unit_name">{{ __('messages.manufacturing_setting.unit_of_measures.name') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control" name="name"
                                                                required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="type">{{ __('messages.manufacturing_setting.unit_of_measures.type') }}<span
                                                                    class="required">*</span></label>
                                                            <select class="form-control" name="type" required>
                                                                <option value="small">Small</option>
                                                                <option value="medium">Medium</option>
                                                                <option value="large">Large</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="category_id">{{ __('messages.manufacturing_setting.unit_of_measures.category') }}<span
                                                                    class="required">*</span></label>
                                                            <select class="form-control" name="category_id"
                                                                id="categorySelect" required>
                                                                <!-- Options will be loaded via AJAX -->
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="rounding_precision">{{ __('messages.manufacturing_setting.unit_of_measures.rounding_precision') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="number" class="form-control"
                                                                name="rounding_precision" step="0.01" required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    name="is_active" id="isActive" checked>
                                                                <label class="custom-control-label"
                                                                    for="isActive">{{ __('messages.manufacturing_setting.unit_of_measures.is_active') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="unitOfMeasureSaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Unit of Measure Edit Modal -->
                                <div class="modal fade" id="editUnitOfMeasureModal" tabindex="-1" role="dialog"
                                    aria-labelledby="editUnitOfMeasureModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUnitOfMeasureModalLabel">
                                                    {{ __('messages.manufacturing_setting.unit_of_measures.edit') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="editUnitOfMeasureForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="editUnitOfMeasureValidationErrorsBox"></div>
                                                    <input type="hidden" name="unit_id" id="unitId">
                                                    <div class="row">
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_unit_name">{{ __('messages.manufacturing_setting.unit_of_measures.name') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control" name="name"
                                                                id="editUnitName" required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_type">{{ __('messages.manufacturing_setting.unit_of_measures.type') }}<span
                                                                    class="required">*</span></label>
                                                            <select class="form-control" name="type" id="editType"
                                                                required>
                                                                <option value="small">Small</option>
                                                                <option value="medium">Medium</option>
                                                                <option value="large">Large</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_category_id">{{ __('messages.manufacturing_setting.unit_of_measures.category') }}<span
                                                                    class="required">*</span></label>
                                                            <select class="form-control" name="category_id"
                                                                id="editCategorySelect" required>
                                                                <!-- Options will be loaded via AJAX -->
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_rounding_precision">{{ __('messages.manufacturing_setting.unit_of_measures.rounding_precision') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="number" class="form-control"
                                                                name="rounding_precision" id="editRoundingPrecision"
                                                                step="0.01" required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    name="is_active" id="editIsActive">
                                                                <label class="custom-control-label"
                                                                    for="editIsActive">{{ __('messages.manufacturing_setting.unit_of_measures.is_active') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="editUnitOfMeasureSaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Material Codes Tab -->
                            <div class="tab-pane" id="material-codes" role="tabpanel">
                                <div class="d-flex justify-content-between mb-4">
                                    <h4>{{ __('messages.manufacturing_setting.material_codes.title') }}</h4>
                                    {{-- <button type="button" class="btn btn-primary form-btn" data-toggle="modal"
                                        data-target="#addMaterialCodeModal">
                                        {{ __('messages.manufacturing_setting.material_codes.add') }}
                                    </button> --}}
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="materialCodeTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.manufacturing_setting.material_codes.material_code') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.material_codes.material_number') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.material_codes.routing_code') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.material_codes.routing_number') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.material_codes.manufacture_order_code') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.material_codes.manufacture_order_number') }}
                                                </th>
                                                <th>{{ __('messages.manufacturing_setting.material_codes.working_hours') }}
                                                </th>
                                                <th>{{ __('messages.common.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <!-- Material Code Add Modal -->
                                <div class="modal fade" id="addMaterialCodeModal" tabindex="-1" role="dialog"
                                    aria-labelledby="addMaterialCodeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addMaterialCodeModalLabel">
                                                    {{ __('messages.manufacturing_setting.material_codes.add') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="addMaterialCodeForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="materialCodeValidationErrorsBox"></div>
                                                    <div class="row">
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="material_code">{{ __('messages.manufacturing_setting.material_codes.material_code') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="material_code" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="material_number">{{ __('messages.manufacturing_setting.material_codes.material_number') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="material_number" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="routing_code">{{ __('messages.manufacturing_setting.material_codes.routing_code') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="routing_code" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="routing_number">{{ __('messages.manufacturing_setting.material_codes.routing_number') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="routing_number" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="manufacture_order_code">{{ __('messages.manufacturing_setting.material_codes.manufacture_order_code') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="manufacture_order_code" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="manufacture_order_number">{{ __('messages.manufacturing_setting.material_codes.manufacture_order_number') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="manufacture_order_number" required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="working_hours">{{ __('messages.manufacturing_setting.material_codes.working_hours') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="working_hours" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="materialCodeSaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Material Code Edit Modal -->
                                <div class="modal fade" id="editMaterialCodeModal" tabindex="-1" role="dialog"
                                    aria-labelledby="editMaterialCodeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editMaterialCodeModalLabel">
                                                    {{ __('messages.manufacturing_setting.material_codes.edit') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="editMaterialCodeForm">
                                                <div class="modal-body">
                                                    <div class="alert alert-danger d-none"
                                                        id="editMaterialCodeValidationErrorsBox"></div>
                                                    <input type="hidden" name="material_code_id" id="materialCodeId">
                                                    <div class="row">
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="edit_material_code">{{ __('messages.manufacturing_setting.material_codes.material_code') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="material_code" id="editMaterialCode" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="edit_material_number">{{ __('messages.manufacturing_setting.material_codes.material_number') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="material_number" id="editMaterialNumber" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="edit_routing_code">{{ __('messages.manufacturing_setting.material_codes.routing_code') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="routing_code" id="editRoutingCode" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="edit_routing_number">{{ __('messages.manufacturing_setting.material_codes.routing_number') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="routing_number" id="editRoutingNumber" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="edit_manufacture_order_code">{{ __('messages.manufacturing_setting.material_codes.manufacture_order_code') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="manufacture_order_code"
                                                                id="editManufactureOrderCode" required>
                                                        </div>
                                                        <div class="form-group col-sm-6">
                                                            <label
                                                                for="edit_manufacture_order_number">{{ __('messages.manufacturing_setting.material_codes.manufacture_order_number') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="manufacture_order_number"
                                                                id="editManufactureOrderNumber" required>
                                                        </div>
                                                        <div class="form-group col-sm-12">
                                                            <label
                                                                for="edit_working_hours">{{ __('messages.manufacturing_setting.material_codes.working_hours') }}<span
                                                                    class="required">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="working_hours" id="editWorkingHours" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"
                                                        id="editMaterialCodeSaveBtn">{{ __('messages.common.save') }}</button>
                                                    <button type="button" class="btn btn-light"
                                                        data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
@endsection

@section('scripts')
    <script>
        'use strict';

        // URLs
        let workingHourCreateUrl = "{{ route('manufacturing_settings.working-hours.store') }}";
        let workingHourUrl = "{{ route('manufacturing_settings.index', ['section' => 'working_hours']) }}";
        let workingHourEditUrl = "{{ route('manufacturing_settings.working-hours.edit', ['working_hour' => ':id']) }}";
        let workingHourUpdateUrl = "{{ route('manufacturing_settings.working-hours.update', ['working_hour' => ':id']) }}";
        let workingHourDeleteUrl =
            "{{ route('manufacturing_settings.working-hours.destroy', ['working_hour' => ':id']) }}";

        let unitOfMeasureCategoryCreateUrl = "{{ route('manufacturing_settings.unit-of-measure-categories.store') }}";
        let unitOfMeasureCategoryUrl =
            "{{ route('manufacturing_settings.index', ['section' => 'unit_of_measure_categories']) }}";
        let unitOfMeasureCategoryEditUrl =
            "{{ route('manufacturing_settings.unit-of-measure-categories.edit', ['unit_of_measure_category' => ':id']) }}";
        let unitOfMeasureCategoryUpdateUrl =
            "{{ route('manufacturing_settings.unit-of-measure-categories.update', ['unit_of_measure_category' => ':id']) }}";
        let unitOfMeasureCategoryDeleteUrl =
            "{{ route('manufacturing_settings.unit-of-measure-categories.destroy', ['unit_of_measure_category' => ':id']) }}";

        let unitOfMeasureCreateUrl = "{{ route('manufacturing_settings.unit-of-measures.store') }}";
        let unitOfMeasureUrl = "{{ route('manufacturing_settings.index', ['section' => 'unit_of_measures']) }}";
        let unitOfMeasureEditUrl =
            "{{ route('manufacturing_settings.unit-of-measures.edit', ['unit_of_measure' => ':id']) }}";
        let unitOfMeasureUpdateUrl =
            "{{ route('manufacturing_settings.unit-of-measures.update', ['unit_of_measure' => ':id']) }}";
        let unitOfMeasureDeleteUrl =
            "{{ route('manufacturing_settings.unit-of-measures.destroy', ['unit_of_measure' => ':id']) }}";
        let getUnitOfMeasureCategoriesUrl = "{{ route('manufacturing_settings.get-unit-of-measure-categories') }}";

        let materialCodeCreateUrl = "{{ route('manufacturing_settings.material-codes.store') }}";
        let materialCodeUrl = "{{ route('manufacturing_settings.index', ['section' => 'material_codes']) }}";
        let materialCodeEditUrl = "{{ route('manufacturing_settings.material-codes.edit', ['material_code' => ':id']) }}";
        let materialCodeUpdateUrl =
            "{{ route('manufacturing_settings.material-codes.update', ['material_code' => ':id']) }}";
        let materialCodeDeleteUrl =
            "{{ route('manufacturing_settings.material-codes.destroy', ['material_code' => ':id']) }}";

        // Initialize all dataTables when their respective tabs are shown
        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("href");

                if (target === '#working-hours') {
                    initWorkingHourTable();
                } else if (target === '#unit-of-measure-categories') {
                    initUnitOfMeasureCategoryTable();
                } else if (target === '#unit-of-measures') {
                    initUnitOfMeasureTable();
                } else if (target === '#material-codes') {
                    initMaterialCodeTable();
                }
            });

            // Initialize the first tab's table
            initWorkingHourTable();
            loadUnitOfMeasureCategories();

            // Load Unit of Measure Categories for select dropdown
            function loadUnitOfMeasureCategories() {
                $.ajax({
                    url: getUnitOfMeasureCategoriesUrl,
                    type: 'GET',
                    success: function(result) {
                        let options = '<option value="">Select Category</option>';
                        result.data.forEach(function(category) {
                            options +=
                                `<option value="${category.id}">${category.category_name}</option>`;
                        });
                        $('#categorySelect').html(options);
                        $('#editCategorySelect').html(options);
                    }
                });
            }
        });

        // Working Hours Functions
        function initWorkingHourTable() {
            if ($.fn.DataTable.isDataTable('#workingHourTable')) {
                $('#workingHourTable').DataTable().destroy();
            }

            let workingHourTable = $('#workingHourTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: workingHourUrl,
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'hours_per_day',
                        name: 'hours_per_day'
                    },
                    {
                        data: function(row) {
                            return `
                                <a title="Edit" class="btn btn-warning action-btn edit-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a title="Delete" class="btn btn-danger action-btn delete-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                        },
                        name: 'id',
                        width: '100px'
                    }
                ],
                responsive: true
            });

            // Add Working Hour
            $(document).on('submit', '#addWorkingHourForm', function(e) {
                e.preventDefault();
                processingBtn('#addWorkingHourForm', '#workingHourSaveBtn', 'loading');

                $.ajax({
                    url: workingHourCreateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addWorkingHourModal').modal('hide');
                            workingHourTable.ajax.reload(null, false);
                            $('#addWorkingHourForm')[0].reset();
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#addWorkingHourForm', '#workingHourSaveBtn');
                    }
                });
            });

            // Edit Working Hour
            $(document).on('click', '.edit-btn', function() {
                let workingHourId = $(this).data('id');
                $.ajax({
                    url: workingHourEditUrl.replace(':id', workingHourId),
                    type: 'GET',
                    success: function(result) {
                        if (result.success) {
                            $('#workingHourId').val(result.data.id);
                            $('#editName').val(result.data.name);
                            $('#editHoursPerDay').val(result.data.hours_per_day);
                            $('#editWorkingHourModal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#editWorkingHourForm', function(e) {
                e.preventDefault();
                processingBtn('#editWorkingHourForm', '#editWorkingHourSaveBtn', 'loading');

                let workingHourId = $('#workingHourId').val();
                $.ajax({
                    url: workingHourUpdateUrl.replace(':id', workingHourId),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editWorkingHourModal').modal('hide');
                            workingHourTable.ajax.reload(null, false);
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#editWorkingHourForm', '#editWorkingHourSaveBtn');
                    }
                });
            });

            // Delete Working Hour
            $(document).on('click', '.delete-btn', function() {
                let workingHourId = $(this).data('id');
                deleteItem(
                    workingHourDeleteUrl.replace(':id', workingHourId),
                    '#workingHourTable',
                    "{{ __('messages.manufacturing_setting.working_hours.delete_confirm') }}"
                );
            });
        }

        // Unit of Measure Category Functions
        function initUnitOfMeasureCategoryTable() {
            if ($.fn.DataTable.isDataTable('#unitOfMeasureCategoryTable')) {
                $('#unitOfMeasureCategoryTable').DataTable().destroy();
            }

            let unitOfMeasureCategoryTable = $('#unitOfMeasureCategoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: unitOfMeasureCategoryUrl,
                },
                columns: [{
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: function(row) {
                            return `
                                <a title="Edit" class="btn btn-warning action-btn edit-category-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a title="Delete" class="btn btn-danger action-btn delete-category-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                        },
                        name: 'id',
                        width: '100px'
                    }
                ],
                responsive: true
            });

            // Add Unit of Measure Category
            $(document).on('submit', '#addUnitOfMeasureCategoryForm', function(e) {
                e.preventDefault();
                processingBtn('#addUnitOfMeasureCategoryForm', '#unitOfMeasureCategorySaveBtn', 'loading');

                $.ajax({
                    url: unitOfMeasureCategoryCreateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addUnitOfMeasureCategoryModal').modal('hide');
                            unitOfMeasureCategoryTable.ajax.reload(null, false);
                            $('#addUnitOfMeasureCategoryForm')[0].reset();
                            loadUnitOfMeasureCategories
                                (); // Reload categories for Unit of Measure dropdown
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#addUnitOfMeasureCategoryForm', '#unitOfMeasureCategorySaveBtn');
                    }
                });
            });

            // Edit Unit of Measure Category
            $(document).on('click', '.edit-category-btn', function() {
                let categoryId = $(this).data('id');
                $.ajax({
                    url: unitOfMeasureCategoryEditUrl.replace(':id', categoryId),
                    type: 'GET',
                    success: function(result) {
                        if (result.success) {
                            $('#categoryId').val(result.data.id);
                            $('#editCategoryName').val(result.data.category_name);
                            $('#editUnitOfMeasureCategoryModal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#editUnitOfMeasureCategoryForm', function(e) {
                e.preventDefault();
                processingBtn('#editUnitOfMeasureCategoryForm', '#editUnitOfMeasureCategorySaveBtn', 'loading');

                let categoryId = $('#categoryId').val();
                $.ajax({
                    url: unitOfMeasureCategoryUpdateUrl.replace(':id', categoryId),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editUnitOfMeasureCategoryModal').modal('hide');
                            unitOfMeasureCategoryTable.ajax.reload(null, false);
                            loadUnitOfMeasureCategories
                                (); // Reload categories for Unit of Measure dropdown
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#editUnitOfMeasureCategoryForm',
                            '#editUnitOfMeasureCategorySaveBtn');
                    }
                });
            });

            // Delete Unit of Measure Category
            $(document).on('click', '.delete-category-btn', function() {
                let categoryId = $(this).data('id');
                deleteItem(
                    unitOfMeasureCategoryDeleteUrl.replace(':id', categoryId),
                    '#unitOfMeasureCategoryTable',
                    "{{ __('messages.manufacturing_setting.unit_of_measure_categories.delete_confirm') }}"
                );
            });
        }

        // Unit of Measure Functions
        function initUnitOfMeasureTable() {
            if ($.fn.DataTable.isDataTable('#unitOfMeasureTable')) {
                $('#unitOfMeasureTable').DataTable().destroy();
            }

            let unitOfMeasureTable = $('#unitOfMeasureTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: unitOfMeasureUrl,
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'category.category_name',
                        name: 'category.category_name'
                    },
                    {
                        data: 'rounding_precision',
                        name: 'rounding_precision'
                    },
                    {
                        data: function(row) {
                            return row.is_active ?
                                '<span class="badge badge-success">Active</span>' :
                                '<span class="badge badge-danger">Inactive</span>';
                        },
                        name: 'is_active'
                    },
                    {
                        data: function(row) {
                            return `
                                <a title="Edit" class="btn btn-warning action-btn edit-unit-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a title="Delete" class="btn btn-danger action-btn delete-unit-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                        },
                        name: 'id',
                        width: '100px'
                    }
                ],
                responsive: true
            });

            // Add Unit of Measure
            $(document).on('submit', '#addUnitOfMeasureForm', function(e) {
                e.preventDefault();
                processingBtn('#addUnitOfMeasureForm', '#unitOfMeasureSaveBtn', 'loading');

                $.ajax({
                    url: unitOfMeasureCreateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addUnitOfMeasureModal').modal('hide');
                            unitOfMeasureTable.ajax.reload(null, false);
                            $('#addUnitOfMeasureForm')[0].reset();
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#addUnitOfMeasureForm', '#unitOfMeasureSaveBtn');
                    }
                });
            });

            // Edit Unit of Measure
            $(document).on('click', '.edit-unit-btn', function() {
                let unitId = $(this).data('id');
                $.ajax({
                    url: unitOfMeasureEditUrl.replace(':id', unitId),
                    type: 'GET',
                    success: function(result) {
                        if (result.success) {
                            $('#unitId').val(result.data.id);
                            $('#editUnitName').val(result.data.name);
                            $('#editType').val(result.data.type);
                            $('#editCategorySelect').val(result.data.category_id);
                            $('#editRoundingPrecision').val(result.data.rounding_precision);
                            $('#editIsActive').prop('checked', result.data.is_active);
                            $('#editUnitOfMeasureModal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#editUnitOfMeasureForm', function(e) {
                e.preventDefault();
                processingBtn('#editUnitOfMeasureForm', '#editUnitOfMeasureSaveBtn', 'loading');

                let unitId = $('#unitId').val();
                $.ajax({
                    url: unitOfMeasureUpdateUrl.replace(':id', unitId),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editUnitOfMeasureModal').modal('hide');
                            unitOfMeasureTable.ajax.reload(null, false);
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#editUnitOfMeasureForm', '#editUnitOfMeasureSaveBtn');
                    }
                });
            });

            // Delete Unit of Measure
            $(document).on('click', '.delete-unit-btn', function() {
                let unitId = $(this).data('id');
                deleteItem(
                    unitOfMeasureDeleteUrl.replace(':id', unitId),
                    '#unitOfMeasureTable',
                    "{{ __('messages.manufacturing_setting.unit_of_measures.delete_confirm') }}"
                );
            });
        }

        // Material Code Functions
        function initMaterialCodeTable() {
            if ($.fn.DataTable.isDataTable('#materialCodeTable')) {
                $('#materialCodeTable').DataTable().destroy();
            }

            let materialCodeTable = $('#materialCodeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: materialCodeUrl,
                },
                columns: [{
                        data: 'material_code',
                        name: 'material_code'
                    },
                    {
                        data: 'material_number',
                        name: 'material_number'
                    },
                    {
                        data: 'routing_code',
                        name: 'routing_code'
                    },
                    {
                        data: 'routing_number',
                        name: 'routing_number'
                    },
                    {
                        data: 'manufacture_order_code',
                        name: 'manufacture_order_code'
                    },
                    {
                        data: 'manufacture_order_number',
                        name: 'manufacture_order_number'
                    },
                    {
                        data: 'working_hours',
                        name: 'working_hours'
                    },
                    {
                        data: function(row) {
                            return `
                                <a title="Edit" class="btn btn-warning action-btn edit-material-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a title="Delete" class="btn btn-danger action-btn delete-material-btn" data-id="${row.id}" href="#">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                        },
                        name: 'id',
                        width: '100px'
                    }
                ],
                responsive: true
            });

            // Add Material Code
            $(document).on('submit', '#addMaterialCodeForm', function(e) {
                e.preventDefault();
                processingBtn('#addMaterialCodeForm', '#materialCodeSaveBtn', 'loading');

                $.ajax({
                    url: materialCodeCreateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#addMaterialCodeModal').modal('hide');
                            materialCodeTable.ajax.reload(null, false);
                            $('#addMaterialCodeForm')[0].reset();
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#addMaterialCodeForm', '#materialCodeSaveBtn');
                    }
                });
            });

            // Edit Material Code
            $(document).on('click', '.edit-material-btn', function() {
                let materialCodeId = $(this).data('id');
                $.ajax({
                    url: materialCodeEditUrl.replace(':id', materialCodeId),
                    type: 'GET',
                    success: function(result) {
                        if (result.success) {
                            $('#materialCodeId').val(result.data.id);
                            $('#editMaterialCode').val(result.data.material_code);
                            $('#editMaterialNumber').val(result.data.material_number);
                            $('#editRoutingCode').val(result.data.routing_code);
                            $('#editRoutingNumber').val(result.data.routing_number);
                            $('#editManufactureOrderCode').val(result.data.manufacture_order_code);
                            $('#editManufactureOrderNumber').val(result.data.manufacture_order_number);
                            $('#editWorkingHours').val(result.data.working_hours);
                            $('#editMaterialCodeModal').modal('show');
                        }
                    }
                });
            });

            $(document).on('submit', '#editMaterialCodeForm', function(e) {
                e.preventDefault();
                processingBtn('#editMaterialCodeForm', '#editMaterialCodeSaveBtn', 'loading');

                let materialCodeId = $('#materialCodeId').val();
                $.ajax({
                    url: materialCodeUpdateUrl.replace(':id', materialCodeId),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $('#editMaterialCodeModal').modal('hide');
                            materialCodeTable.ajax.reload(null, false);
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#editMaterialCodeForm', '#editMaterialCodeSaveBtn');
                    }
                });
            });

            // Delete Material Code
            $(document).on('click', '.delete-material-btn', function() {
                let materialCodeId = $(this).data('id');
                deleteItem(
                    materialCodeDeleteUrl.replace(':id', materialCodeId),
                    '#materialCodeTable',
                    "{{ __('messages.manufacturing_setting.material_codes.delete_confirm') }}"
                );
            });
        }

        // Common Functions
        function processingBtn(button, btnId, action = 'loading') {
            if (action === 'loading') {
                $(btnId).prop('disabled', true);
                $(button).find('button[type="submit"]').html(
                    '<span class="spinner-border spinner-border-sm"></span> Processing...'
                );
            } else {
                $(btnId).prop('disabled', false);
                $(button).find('button[type="submit"]').html('{{ __('messages.common.save') }}');
            }
        }

        function displaySuccessMessage(message) {
            toastr.success(message, 'Success');
        }

        function displayErrorMessage(message) {
            toastr.error(message, 'Error');
        }

        function deleteItem(url, tableId, message) {
            if (confirm(message)) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            $(tableId).DataTable().ajax.reload(null, false);
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    }
                });
            }
        }

        $(document).ready(function() {
            // Initialize all modals properly
            $('.modal').each(function() {
                $(this).modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: false
                });
            });

            // Fix for multiple modals
            $(document).on('show.bs.modal', '.modal', function() {
                const zIndex = 1040 + (10 * $('.modal:visible').length);
                $(this).css('z-index', zIndex);
                setTimeout(() => {
                    $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass(
                        'modal-stack');
                }, 0);
            });

            // Properly handle modal closing
            $(document).on('hidden.bs.modal', '.modal', function() {
                if ($('.modal:visible').length > 0) {
                    // Maintain backdrop for remaining open modals
                    $('body').addClass('modal-open');
                }
            });

            // Fix for clicking outside modal
            $(document).on('click', function(e) {
                if ($(e.target).hasClass('modal')) {
                    const modalId = $(e.target).attr('id');
                    $('#' + modalId).modal('hide');
                }
            });

            // Focus first input when modal shown
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('input:visible:first').focus();
            });

            // Reset form when modal hidden
            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
            });

            // Initialize the first tab's table
            initWorkingHourTable();
            loadUnitOfMeasureCategories();
        });
    </script>
@endsection
