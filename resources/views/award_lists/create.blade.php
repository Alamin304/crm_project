@extends('layouts.app')

@section('title')
    {{ __('messages.award_lists.add_award_lists') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.award_lists.add_award_lists') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('award-lists.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.award_lists.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormAward']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('award_name', __('messages.award_lists.award_name') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('award_name', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'awardName',
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('employee_name', __('messages.award_lists.employee_name') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::select(
                                        'employee_name',
                                        [
                                            'John Doe' => 'John Doe',
                                            'Jane Smith' => 'Jane Smith',
                                            'Robert Johnson' => 'Robert Johnson',
                                            'Emily Davis' => 'Emily Davis',
                                            'Michael Wilson' => 'Michael Wilson',
                                        ],
                                        null,
                                        [
                                            'class' => 'form-control select2',
                                            'required',
                                            'id' => 'employeeName',
                                            'placeholder' => __('messages.award_lists.select_employee_name'),
                                        ],
                                    ) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('award_by', __('messages.award_lists.award_by') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('award_by', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'awardBy',
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('date', __('messages.award_lists.date') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::date('date', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'awardDate',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('gift_item', __('messages.award_lists.gift_item') . ':') }}
                                    {{ Form::text('gift_item', null, [
                                        'class' => 'form-control',
                                        'id' => 'giftItem',
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('award_description', __('messages.award_lists.award_description') . ':') }}
                                    {{ Form::textarea('award_description', null, [
                                        'class' => 'form-control summernote-simple',
                                        'id' => 'awardDescription',
                                        'rows' => 4,
                                    ]) }}
                                </div>
                            </div>
                            <div class="text-right mt-3 mr-1">
                                {{ Form::button(__('messages.common.submit'), [
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary',
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
    <script>
        let awardCreateUrl = "{{ route('award-lists.store') }}";

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: "{{ __('messages.award_lists.select_employee_name') }}",
            });

            $('#awardDescription').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });

        $(document).on('submit', '#addNewFormAward', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormAward', '#btnSave', 'loading');

            // Get Summernote HTML and convert to plain text
            let descriptionHtml = $('#awardDescription').summernote('code');
            let descriptionText = $('<div>').html(descriptionHtml).text().trim();

            if (!descriptionText) {
                displayErrorMessage('Award description must not be empty or whitespace.');
                processingBtn('#addNewFormAward', '#btnSave', 'reset');
                return false;
            }

            // Inject plain text back into the textarea before serializing form
            $('#awardDescription').val(descriptionText);

            $.ajax({
                url: awardCreateUrl,
                type: 'POST',
                data: $(this).serialize(), // now includes plain text description
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('award-lists.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewFormAward', '#btnSave');
                },
            });
        });

        function processErrorMessage(errors) {
            let errorHtml = '<ul>';
            $.each(errors, function(key, value) {
                errorHtml += '<li>' + value[0] + '</li>';
            });
            errorHtml += '</ul>';
            $('#validationErrorsBox').html(errorHtml);
            $('#validationErrorsBox').removeClass('d-none');
        }
    </script>
@endsection
