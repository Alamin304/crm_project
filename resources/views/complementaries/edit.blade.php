@extends('layouts.app')

@section('title')
    {{ __('messages.complementaries.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.complementaries.edit') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('complementaries.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.complementaries.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'editComplementaryForm']) }}
                        {{ Form::hidden('id', $complementary->id, ['id' => 'complementary_id']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('room_type', __('messages.complementaries.room_type') . ':') }}
                                    {{ Form::text('room_type', $complementary->room_type, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('complementary', __('messages.complementaries.complementary') . ':') }}<span class="required">*</span>
                                    {{ Form::text('complementary', $complementary->complementary, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('rate', __('messages.complementaries.rate') . ':') }}<span class="required">*</span>
                                    {{ Form::number('rate', $complementary->rate, [
                                        'class' => 'form-control',
                                        'required',
                                        'autocomplete' => 'off',
                                        'step' => '0.01',
                                        'min' => '0'
                                    ]) }}
                                </div>
                            </div>

                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
                                    'id' => 'btnSave',
                                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."
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
@endsection

@section('scripts')
    <script>
        'use strict';

        $(document).on('submit', '#editComplementaryForm', function (event) {
            event.preventDefault();
            processingBtn('#editComplementaryForm', '#btnSave', 'loading');
            let id = $('#complementary_id').val();

            $.ajax({
                url: route('complementaries.update', id),
                type: 'PUT',
                data: $(this).serialize(),
                success: function (result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('complementaries.index') }}";
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function () {
                    processingBtn('#editComplementaryForm', '#btnSave');
                }
            });
        });
    </script>
@endsection
