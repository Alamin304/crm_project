@extends('layouts.app')

@section('title')
    {{ __('messages.complementaries.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
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
                        {{ Form::open(['id' => 'editAwardListForm']) }}
                        {{ Form::hidden('id', $awardList->id, ['id' => 'award_list_id']) }}

                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('award_name', __('messages.award_lists.award_name').':') }}
                                    {{ Form::text('award_name', $awardList->award_name, ['class' => 'form-control', 'required']) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('employee_name', __('messages.award_lists.employee_name').':') }}
                                    {{ Form::text('employee_name', $awardList->employee_name, ['class' => 'form-control', 'required']) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('award_by', __('messages.award_lists.award_by').':') }}
                                    {{ Form::text('award_by', $awardList->award_by, ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group col-sm-6">
                                    {{ Form::label('date', __('messages.award_lists.date').':') }}
                                    {{ Form::date('date', $awardList->date, ['class' => 'form-control', 'required']) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('gift_item', __('messages.award_lists.gift_item').':') }}
                                    {{ Form::text('gift_item', $awardList->gift_item, ['class' => 'form-control']) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('award_description', __('messages.award_lists.award_description').':') }}
                                    {{ Form::textarea('award_description', $awardList->award_description, ['class' => 'form-control summernote']) }}
                                </div>
                            </div>

                            <div class="text-right mt-3">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
                                    'id' => 'btnSave',
                                    'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> " . __('messages.common.processing') . "..."
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
    <script>
        'use strict';

        $('.summernote').summernote({
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });

        $(document).on('submit', '#editAwardListForm', function (event) {
            event.preventDefault();
            processingBtn('#editAwardListForm', '#btnSave', 'loading');
            let id = $('#award_list_id').val();

            $.ajax({
                url: route('award-lists.update', id),
                type: 'PUT',
                data: $(this).serialize(),
                success: function (result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('award-lists.index') }}";
                    }
                },
                error: function (result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function () {
                    processingBtn('#editAwardListForm', '#btnSave');
                }
            });
        });
    </script>
@endsection
