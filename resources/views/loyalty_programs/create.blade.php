@extends('layouts.app')
@section('title')
    {{ __('messages.loyalty_programs.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <style>
        .rule-container {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .add-rule-btn {
            margin-bottom: 20px;
        }

        .remove-rule-btn {
            margin-top: 10px;
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.loyalty_programs.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('loyalty-programs.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.loyalty_programs.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addNewFormLoyaltyProgram']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    {{ Form::label('name', __('messages.loyalty_programs.name').':') }}<span class="required">*</span>
                                    {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'loyaltyProgramName']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('customer_group', __('messages.loyalty_programs.customer_group').':') }}<span class="required">*</span>
                                    {{ Form::select('customer_group', [
                                        'regular' => 'Regular',
                                        'premium' => 'Premium',
                                        'vip' => 'VIP',
                                        'gold' => 'Gold',
                                        'silver' => 'Silver'
                                    ], null, ['class' => 'form-control select2', 'id' => 'customerGroup', 'placeholder' => 'Select Customer Group', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('customer', __('messages.loyalty_programs.customer').':') }}<span class="required">*</span>
                                    {{ Form::select('customer', [
                                        'new' => 'New Customer',
                                        'existing' => 'Existing Customer',
                                        'loyal' => 'Loyal Customer',
                                        'returning' => 'Returning Customer'
                                    ], null, ['class' => 'form-control select2', 'id' => 'customer', 'placeholder' => 'Select Customer Type', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('start_date', __('messages.loyalty_programs.start_date').':') }}<span class="required">*</span>
                                    {{ Form::date('start_date', null, ['class' => 'form-control', 'required', 'id' => 'startDate']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('end_date', __('messages.loyalty_programs.end_date').':') }}<span class="required">*</span>
                                    {{ Form::date('end_date', null, ['class' => 'form-control', 'required', 'id' => 'endDate']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('rule_base', __('messages.loyalty_programs.rule_base').':') }}<span class="required">*</span>
                                    {{ Form::select('rule_base', [
                                        'purchase_amount' => 'Purchase Amount',
                                        'product_category' => 'Product Category',
                                        'specific_product' => 'Specific Product'
                                    ], null, ['class' => 'form-control select2', 'id' => 'ruleBase', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('minimum_purchase', __('messages.loyalty_programs.minimum_purchase').':') }}<span class="required">*</span>
                                    {{ Form::number('minimum_purchase', 0, ['class' => 'form-control', 'required', 'min' => 0, 'step' => '0.01', 'id' => 'minimumPurchase']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('account_creation_point', __('messages.loyalty_programs.account_creation_point').':') }}<span class="required">*</span>
                                    {{ Form::number('account_creation_point', 0, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'accountCreationPoint']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('birthday_point', __('messages.loyalty_programs.birthday_point').':') }}<span class="required">*</span>
                                    {{ Form::number('birthday_point', 0, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'birthdayPoint']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('redeem_type', __('messages.loyalty_programs.redeem_type').':') }}<span class="required">*</span>
                                    {{ Form::select('redeem_type', [
                                        'fixed_amount' => 'Fixed Amount',
                                        'percentage' => 'Percentage',
                                        'free_product' => 'Free Product'
                                    ], null, ['class' => 'form-control select2', 'id' => 'redeemType', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('minimum_point_to_redeem', __('messages.loyalty_programs.minimum_point_to_redeem').':') }}<span class="required">*</span>
                                    {{ Form::number('minimum_point_to_redeem', 0, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'minimumPointToRedeem']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('max_amount_receive', __('messages.loyalty_programs.max_amount_receive').':') }}<span class="required">*</span>
                                    {{ Form::number('max_amount_receive', 0, ['class' => 'form-control', 'required', 'min' => 0, 'step' => '0.01', 'id' => 'maxAmountReceive']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    {{ Form::label('status', __('messages.loyalty_programs.status').':') }}<span class="required">*</span>
                                    {{ Form::select('status', [
                                        'enabled' => 'Enabled',
                                        'disabled' => 'Disabled'
                                    ], 'enabled', ['class' => 'form-control select2', 'id' => 'status', 'required']) }}
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="form-check">
                                        {{ Form::checkbox('redeem_in_portal', 1, false, ['class' => 'form-check-input', 'id' => 'redeemInPortal']) }}
                                        {{ Form::label('redeem_in_portal', __('messages.loyalty_programs.redeem_in_portal'), ['class' => 'form-check-label']) }}
                                    </div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <div class="form-check">
                                        {{ Form::checkbox('redeem_in_pos', 1, false, ['class' => 'form-check-input', 'id' => 'redeemInPos']) }}
                                        {{ Form::label('redeem_in_pos', __('messages.loyalty_programs.redeem_in_pos'), ['class' => 'form-check-label']) }}
                                    </div>
                                </div>

                                <!-- Dynamic Rules Section -->
                                <div class="col-12">
                                    <h5>{{ __('messages.loyalty_programs.rules') }}</h5>
                                    <button type="button" class="btn btn-primary add-rule-btn" id="addRuleBtn">
                                        <i class="fas fa-plus"></i> {{ __('Add Rule') }}
                                    </button>

                                    <div id="rulesContainer">
                                        <!-- Rules will be added here dynamically -->
                                    </div>
                                </div>

                                <div class="form-group col-sm-12 mb-0">
                                    {{ Form::label('description', __('messages.loyalty_programs.description').':') }}
                                    {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'loyaltyProgramDescription']) }}
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
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let loyaltyProgramCreateUrl = "{{ route('loyalty-programs.store') }}";
        let ruleCounter = 0;

        $(document).on('submit', '#addNewFormLoyaltyProgram', function(event) {
            event.preventDefault();
            processingBtn('#addNewFormLoyaltyProgram', '#btnSave', 'loading');

            let description = $('<div />').html($('#loyaltyProgramDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#loyaltyProgramDescription').summernote('isEmpty')) {
                $('#loyaltyProgramDescription').val('');
            } else if (empty) {
                displayErrorMessage('Description field must not contain only white space.');
                processingBtn('#addNewFormLoyaltyProgram', '#btnSave', 'reset');
                return false;
            }

            $.ajax({
                url: loyaltyProgramCreateUrl,
                type: 'POST',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = "{{ route('loyalty-programs.index') }}";
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addNewFormLoyaltyProgram', '#btnSave');
                },
            });
        });

        $(document).ready(function() {
            $('#loyaltyProgramDescription').summernote({
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

            // Add initial rule
            addRule();

            // Add rule button click handler
            $('#addRuleBtn').click(function() {
                addRule();
            });

            // Remove rule button handler (delegated)
            $(document).on('click', '.remove-rule-btn', function() {
                $(this).closest('.rule-container').remove();
            });
        });

        // function addRule() {
        //     ruleCounter++;
        //     const ruleHtml = `
        //         <div class="rule-container">
        //             <div class="row">
        //                 <div class="form-group col-sm-4">
        //                     <label for="rule_name_${ruleCounter}">Rule Name</label>
        //                     <input type="text" name="rule_name[]" class="form-control" id="rule_name_${ruleCounter}" required>
        //                 </div>
        //                 <div class="form-group col-sm-2">
        //                     <label for="point_from_${ruleCounter}">Point From</label>
        //                     <input type="number" name="point_from[]" class="form-control" id="point_from_${ruleCounter}" min="0" required>
        //                 </div>
        //                 <div class="form-group col-sm-2">
        //                     <label for="point_to_${ruleCounter}">Point To</label>
        //                     <input type="number" name="point_to[]" class="form-control" id="point_to_${ruleCounter}" min="0" required>
        //                 </div>
        //                 <div class="form-group col-sm-2">
        //                     <label for="point_weight_${ruleCounter}">Point Weight</label>
        //                     <input type="number" name="point_weight[]" class="form-control" id="point_weight_${ruleCounter}" min="0" step="0.01" required>
        //                 </div>
        //                 <div class="form-group col-sm-2">
        //                     <label for="rule_status_${ruleCounter}">Status</label>
        //                     <select name="rule_status[]" class="form-control" id="rule_status_${ruleCounter}" required>
        //                         <option value="enabled">Enabled</option>
        //                         <option value="disabled">Disabled</option>
        //                     </select>
        //                 </div>
        //             </div>
        //             <button type="button" class="btn btn-danger btn-sm remove-rule-btn">
        //                 <i class="fas fa-trash"></i> Remove Rule
        //             </button>
        //         </div>
        //     `;
        //     $('#rulesContainer').append(ruleHtml);
        // }
          $(document).ready(function () {
        let ruleIndex = 0;

        $('#addRuleBtn').on('click', function () {
            const ruleHtml = `
                <div class="rule-container" data-index="${ruleIndex}">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label>Rule Name<span class="text-danger">*</span></label>
                            <input type="text" name="rule_name[]" class="form-control" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Point From<span class="text-danger">*</span></label>
                            <input type="number" name="point_from[]" class="form-control" required min="0">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Point To<span class="text-danger">*</span></label>
                            <input type="number" name="point_to[]" class="form-control" required min="0">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Weight<span class="text-danger">*</span></label>
                            <input type="number" name="point_weight[]" class="form-control" required min="0" step="0.01">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Status</label>
                            <select name="rule_status[]" class="form-control select2">
                                <option value="enabled" selected>Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-rule-btn">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
            $('#rulesContainer').append(ruleHtml);
            ruleIndex++;
            $('.select2').select2(); // Re-initialize select2
        });

        $(document).on('click', '.remove-rule-btn', function () {
            $(this).closest('.rule-container').remove();
        });

        $('.select2').select2();
        $('.summernote-simple').summernote({
            height: 150
        });
    });
    </script>
@endsection
