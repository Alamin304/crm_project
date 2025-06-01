@extends('layouts.app')
@section('title')
    {{ __('messages.membership_rules.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.membership_rules.edit') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('membership-rules.index') }}" class="btn btn-primary form-btn float-right">
                    {{ __('messages.membership_rules.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($membershipRule, ['id' => 'editMembershipRuleForm', 'route' => ['membership-rules.update', $membershipRule->id], 'method' => 'put']) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('name', __('messages.membership_rules.name').':') }}<span class="required">*</span>
                            {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'editMembershipRuleName']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('customer_group', __('messages.membership_rules.customer_group').':') }}<span class="required">*</span>
                            {{ Form::select('customer_group', [
                                'regular' => 'Regular',
                                'premium' => 'Premium',
                                'vip' => 'VIP',
                                'gold' => 'Gold',
                                'silver' => 'Silver'
                            ], null, ['class' => 'form-control select2', 'id' => 'editCustomerGroup', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('customer', __('messages.membership_rules.customer').':') }}<span class="required">*</span>
                            {{ Form::select('customer', [
                                'new' => 'New Customer',
                                'existing' => 'Existing Customer',
                                'loyal' => 'Loyal Customer',
                                'returning' => 'Returning Customer'
                            ], null, ['class' => 'form-control select2', 'id' => 'editCustomer', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('card', __('messages.membership_rules.card').':') }}<span class="required">*</span>
                            {{ Form::select('card', [
                                'standard' => 'Standard Card',
                                'premium' => 'Premium Card',
                                'gold' => 'Gold Card',
                                'platinum' => 'Platinum Card'
                            ], null, ['class' => 'form-control select2', 'id' => 'editCard', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('point_from', __('messages.membership_rules.point_from').':') }}<span class="required">*</span>
                            {{ Form::number('point_from', null, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'editPointFrom']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('point_to', __('messages.membership_rules.point_to').':') }}<span class="required">*</span>
                            {{ Form::number('point_to', null, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'editPointTo']) }}
                        </div>
                        <div class="form-group col-sm-12 mb-0">
                            {{ Form::label('description', __('messages.membership_rules.description').':') }}
                            {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'editMembershipRuleDescription']) }}
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
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        let membershipRuleUrl = "{{ route('membership-rules.index') }}";
        let membershipRuleUpdateUrl = "{{ route('membership-rules.update', $membershipRule->id) }}";

        $(document).ready(function() {
            $('#editMembershipRuleDescription').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            $('.select2').select2({
                width: '100%'
            });
        });

        $(document).on('submit', '#editMembershipRuleForm', function(event) {
            event.preventDefault();
            processingBtn('#editMembershipRuleForm', '#btnEditSave', 'loading');

            let description = $('<div />').html($('#editMembershipRuleDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#editMembershipRuleDescription').summernote('isEmpty')) {
                $('#editMembershipRuleDescription').val('');
            } else if (empty) {
                displayErrorMessage('Description field must not contain only white space.');
                processingBtn('#editMembershipRuleForm', '#btnEditSave', 'reset');
                return false;
            }

            $.ajax({
                url: membershipRuleUpdateUrl,
                type: 'put',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = membershipRuleUrl;
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#editMembershipRuleForm', '#btnEditSave');
                },
            });
        });
    </script>
@endsection
