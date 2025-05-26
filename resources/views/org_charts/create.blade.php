@extends('layouts.app')

@section('title')
    {{ __('messages.org_charts.add_org_chart') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.org_charts.add_org_chart') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('org-charts.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.org_charts.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewOrgChartForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    {{ Form::label('name', __('messages.org_charts.name') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::text('name', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'id' => 'orgChartName',
                                        'placeholder' => __('messages.org_charts.name'),
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('unit_manager', __('messages.org_charts.unit_manager') . ':') }}<span
                                        class="required">*</span>
                                    {{ Form::select('unit_manager', $dummyManagers, null, [
                                        'class' => 'form-control select2',
                                        'required',
                                        'id' => 'unitManager',
                                        'placeholder' => __('messages.org_charts.select_unit_manager'),
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('parent_unit', __('messages.org_charts.parent_unit') . ':') }}
                                    {{ Form::select('parent_unit', $dummyUnits, null, [
                                        'class' => 'form-control select2',
                                        'id' => 'parentUnit',
                                        'placeholder' => __('messages.org_charts.select_parent_unit'),
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('email', __('messages.org_charts.email') . ':') }}
                                    {{ Form::email('email', null, [
                                        'class' => 'form-control',
                                        'id' => 'orgChartEmail',
                                        'placeholder' => __('messages.org_charts.email'),
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('user_name', __('messages.org_charts.user_name') . ':') }}
                                    {{ Form::text('user_name', null, [
                                        'class' => 'form-control',
                                        'id' => 'orgChartUserName',
                                        'placeholder' => __('messages.org_charts.user_name'),
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('host', __('messages.org_charts.host') . ':') }}
                                    {{ Form::text('host', null, [
                                        'class' => 'form-control',
                                        'id' => 'orgChartHost',
                                        'placeholder' => __('messages.org_charts.host'),
                                        'autocomplete' => 'off',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('password', __('messages.org_charts.password') . ':') }}
                                    {{ Form::password('password', [
                                        'class' => 'form-control',
                                        'id' => 'orgChartPassword',
                                        'placeholder' => __('messages.org_charts.password'),
                                        'autocomplete' => 'new-password',
                                    ]) }}
                                </div>

                                <div class="form-group col-sm-12">
                                    {{ Form::label('encryption', __('messages.org_charts.encryption') . ':') }}
                                    {{ Form::select(
                                        'encryption',
                                        [
                                            'no encryption' => __('messages.org_charts.no_encryption'),
                                            'TLS' => 'TLS',
                                            'SSL' => 'SSL',
                                        ],
                                        'no encryption',
                                        [
                                            'class' => 'form-control select2',
                                            'id' => 'orgChartEncryption',
                                        ],
                                    ) }}
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
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let orgChartCreateUrl = "{{ route('org-charts.store') }}";

        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });

        $(document).on('submit', '#addNewOrgChartForm', function(event) {
            event.preventDefault();
            processingBtn('#addNewOrgChartForm', '#btnSave', 'loading');

            $.ajax({
                url: orgChartCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('org-charts.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                    if (result.status === 422) {
                        processErrorMessage(result.responseJSON.errors);
                    }
                },
                complete: function() {
                    processingBtn('#addNewOrgChartForm', '#btnSave');
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
