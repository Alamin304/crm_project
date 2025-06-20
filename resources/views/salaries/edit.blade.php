@extends('layouts.app')
@section('title')
    {{ __('messages.salaries.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <style>
        #statusSwitchEdit.form-check-input {
            width: 3em;
            height: 1.5em;
        }

        #statusSwitchEdit.form-check-input:checked {
            background-color: #0d6efd;
            /* Change the color as needed */
        }

        #statusSwitchEdit.form-check-input::before {
            width: 1.5em;
            height: 1.5em;
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.salaries.edit') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('salaries.index') }}" class="btn btn-primary form-btn">List</a>

            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'editForm']) }}
                        {{ Form::hidden('id', $salary->id, ['id' => 'salary_id']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('employee_id', __('messages.salaries.select_employee') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::select('employee_id', $employees, $salary->employee_id ?? null, ['class' => 'form-control', 'required', 'id' => 'employee_select', 'placeholder' => __('messages.salaries.select_employee')]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('salary', __('messages.salaries.salary_amount') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::number('salary', $salary->salary ?? null, ['class' => 'form-control', 'required', 'id' => 'salary_amount', 'step' => '0.01', 'autocomplete' => 'off']) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('month', __('messages.salaries.month') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::month('month', $salary->month ? \Carbon\Carbon::parse($salary->month)->format('Y-m') : null, ['class' => 'form-control', 'required', 'id' => 'month_picker']) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('is_active', __('messages.salaries.is_active')) }}

                                    <div class="form-check">
                                        {{ Form::radio('is_active', 1, $salary->is_active == 1, ['class' => 'form-check-input', 'id' => 'is_active', 'required']) }}
                                        {{ Form::label('is_active', __('messages.salaries.active'), ['class' => 'form-check-label ml-2']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('is_active', 0, $salary->is_active == 0, ['class' => 'form-check-input', 'id' => 'is_inactive']) }}
                                        {{ Form::label('is_inactive', __('messages.salaries.inactive'), ['class' => 'form-check-label ml-2']) }}
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mr-1">
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
        'use strict';
        $(document).on('submit', '#editForm', function(event) {
            event.preventDefault();
            processingBtn('#editForm', '#btnSave', 'loading');
            let id = $('#salary_id').val();
            let description = $('<div />').
            html($('#editCategoryDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#editCategoryDescription').summernote('isEmpty')) {
                $('#editCategoryDescription').val('');
            } else if (empty) {
                displayErrorMessage(
                    'Description field is not contain only white space');
                processingBtn('#addNewForm', '#btnSave', 'reset');
                return false;
            }
            var departmentSelect = $('#departmentSelect').val();
            if (departmentSelect === '' || departmentSelect === null) {
                displayErrorMessage('{{ __('messages.salaries.select_department') }}');
                return false;
            }

            $.ajax({
                url: route('salaries.update', id),
                type: 'put',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        const url = route('salaries.index', );
                        window.location.href = url;
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#editForm', '#btnSave');
                },
            });
        });

        $(document).ready(function() {

            $('#employee_select').select2({
                width: '100%', // Set the width of the select element
                placeholder: '{{ __('messages.salaries.select_employee') }}', // Placeholder text
                allowClear: true // Allow clearing the selection
            });
        });
    </script>
@endsection
