@extends('layouts.app')
@section('title')
    {{ __('messages.loyalty_programs.edit') }}
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
        <div class="section-header">
            <h1>{{ __('messages.loyalty_programs.edit') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('loyalty-programs.index') }}" class="btn btn-primary form-btn float-right">
                    {{ __('messages.loyalty_programs.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($loyaltyProgram, ['id' => 'editLoyaltyProgramForm', 'route' => ['loyalty-programs.update', $loyaltyProgram->id], 'method' => 'put']) }}
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {{ Form::label('name', __('messages.loyalty_programs.name').':') }}<span class="required">*</span>
                            {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'editLoyaltyProgramName']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('customer_group', __('messages.loyalty_programs.customer_group').':') }}<span class="required">*</span>
                            {{ Form::select('customer_group', [
                                'regular' => 'Regular',
                                'premium' => 'Premium',
                                'vip' => 'VIP',
                                'gold' => 'Gold',
                                'silver' => 'Silver'
                            ], null, ['class' => 'form-control select2', 'id' => 'editCustomerGroup', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('customer', __('messages.loyalty_programs.customer').':') }}<span class="required">*</span>
                            {{ Form::select('customer', [
                                'new' => 'New Customer',
                                'existing' => 'Existing Customer',
                                'loyal' => 'Loyal Customer',
                                'returning' => 'Returning Customer'
                            ], null, ['class' => 'form-control select2', 'id' => 'editCustomer', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('start_date', __('messages.loyalty_programs.start_date').':') }}<span class="required">*</span>
                            {{ Form::date('start_date', null, ['class' => 'form-control', 'required', 'id' => 'editStartDate']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('end_date', __('messages.loyalty_programs.end_date').':') }}<span class="required">*</span>
                            {{ Form::date('end_date', null, ['class' => 'form-control', 'required', 'id' => 'editEndDate']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('rule_base', __('messages.loyalty_programs.rule_base').':') }}<span class="required">*</span>
                            {{ Form::select('rule_base', [
                                'purchase_amount' => 'Purchase Amount',
                                'product_category' => 'Product Category',
                                'specific_product' => 'Specific Product'
                            ], null, ['class' => 'form-control select2', 'id' => 'editRuleBase', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('minimum_purchase', __('messages.loyalty_programs.minimum_purchase').':') }}<span class="required">*</span>
                            {{ Form::number('minimum_purchase', null, ['class' => 'form-control', 'required', 'min' => 0, 'step' => '0.01', 'id' => 'editMinimumPurchase']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('account_creation_point', __('messages.loyalty_programs.account_creation_point').':') }}<span class="required">*</span>
                            {{ Form::number('account_creation_point', null, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'editAccountCreationPoint']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('birthday_point', __('messages.loyalty_programs.birthday_point').':') }}<span class="required">*</span>
                            {{ Form::number('birthday_point', null, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'editBirthdayPoint']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('redeem_type', __('messages.loyalty_programs.redeem_type').':') }}<span class="required">*</span>
                            {{ Form::select('redeem_type', [
                                'fixed_amount' => 'Fixed Amount',
                                'percentage' => 'Percentage',
                                'free_product' => 'Free Product'
                            ], null, ['class' => 'form-control select2', 'id' => 'editRedeemType', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('minimum_point_to_redeem', __('messages.loyalty_programs.minimum_point_to_redeem').':') }}<span class="required">*</span>
                            {{ Form::number('minimum_point_to_redeem', null, ['class' => 'form-control', 'required', 'min' => 0, 'id' => 'editMinimumPointToRedeem']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('max_amount_receive', __('messages.loyalty_programs.max_amount_receive').':') }}<span class="required">*</span>
                            {{ Form::number('max_amount_receive', null, ['class' => 'form-control', 'required', 'min' => 0, 'step' => '0.01', 'id' => 'editMaxAmountReceive']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            {{ Form::label('status', __('messages.loyalty_programs.status').':') }}<span class="required">*</span>
                            {{ Form::select('status', [
                                'enabled' => 'Enabled',
                                'disabled' => 'Disabled'
                            ], null, ['class' => 'form-control select2', 'id' => 'editStatus', 'required']) }}
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="form-check">
                                {{ Form::checkbox('redeem_in_portal', 1, null, ['class' => 'form-check-input', 'id' => 'editRedeemInPortal']) }}
                                {{ Form::label('redeem_in_portal', __('messages.loyalty_programs.redeem_in_portal'), ['class' => 'form-check-label']) }}
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <div class="form-check">
                                {{ Form::checkbox('redeem_in_pos', 1, null, ['class' => 'form-check-input', 'id' => 'editRedeemInPos']) }}
                                {{ Form::label('redeem_in_pos', __('messages.loyalty_programs.redeem_in_pos'), ['class' => 'form-check-label']) }}
                            </div>
                        </div>

                        <!-- Dynamic Rules Section -->
                        <div class="col-12">
                            <h5>{{ __('messages.loyalty_programs.rules') }}</h5>
                            <button type="button" class="btn btn-primary add-rule-btn" id="editAddRuleBtn">
                                <i class="fas fa-plus"></i> {{ __('Add Rule') }}
                            </button>

                            <div id="editRulesContainer">
                                <!-- Rules will be added here dynamically -->
                                @if($loyaltyProgram->rules)
                                    @foreach($loyaltyProgram->rules as $index => $rule)
                                        <div class="rule-container">
                                            <div class="row">
                                                <div class="form-group col-sm-4">
                                                    <label for="edit_rule_name_{{ $index }}">Rule Name</label>
                                                    <input type="text" name="rule_name[]" class="form-control"
                                                        id="edit_rule_name_{{ $index }}" value="{{ $rule['rule_name'] }}" required>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="edit_point_from_{{ $index }}">Point From</label>
                                                    <input type="number" name="point_from[]" class="form-control"
                                                        id="edit_point_from_{{ $index }}" min="0" value="{{ $rule['point_from'] }}" required>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="edit_point_to_{{ $index }}">Point To</label>
                                                    <input type="number" name="point_to[]" class="form-control"
                                                        id="edit_point_to_{{ $index }}" min="0" value="{{ $rule['point_to'] }}" required>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="edit_point_weight_{{ $index }}">Point Weight</label>
                                                    <input type="number" name="point_weight[]" class="form-control"
                                                        id="edit_point_weight_{{ $index }}" min="0" step="0.01"
                                                        value="{{ $rule['point_weight'] }}" required>
                                                </div>
                                                <div class="form-group col-sm-2">
                                                    <label for="edit_rule_status_{{ $index }}">Status</label>
                                                    <select name="rule_status[]" class="form-control" id="edit_rule_status_{{ $index }}" required>
                                                        <option value="enabled" {{ $rule['status'] == 'enabled' ? 'selected' : '' }}>Enabled</option>
                                                        <option value="disabled" {{ $rule['status'] == 'disabled' ? 'selected' : '' }}>Disabled</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm remove-rule-btn">
                                                <i class="fas fa-trash"></i> Remove Rule
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="form-group col-sm-12 mb-0">
                            {{ Form::label('description', __('messages.loyalty_programs.description').':') }}
                            {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'editLoyaltyProgramDescription']) }}
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
        let loyaltyProgramUrl = "{{ route('loyalty-programs.index') }}";
        let loyaltyProgramUpdateUrl = "{{ route('loyalty-programs.update', $loyaltyProgram->id) }}";
        let ruleCounter = {{ $loyaltyProgram->rules ? count($loyaltyProgram->rules) : 0 }};

        $(document).ready(function() {
            $('#editLoyaltyProgramDescription').summernote({
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

            // Add rule button click handler
            $('#editAddRuleBtn').click(function() {
                addRule();
            });

            // Remove rule button handler (delegated)
            $(document).on('click', '.remove-rule-btn', function() {
                $(this).closest('.rule-container').remove();
            });
        });

        function addRule() {
            ruleCounter++;
            const ruleHtml = `
                <div class="rule-container">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="edit_rule_name_${ruleCounter}">Rule Name</label>
                            <input type="text" name="rule_name[]" class="form-control" id="edit_rule_name_${ruleCounter}" required>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="edit_point_from_${ruleCounter}">Point From</label>
                            <input type="number" name="point_from[]" class="form-control" id="edit_point_from_${ruleCounter}" min="0" required>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="edit_point_to_${ruleCounter}">Point To</label>
                            <input type="number" name="point_to[]" class="form-control" id="edit_point_to_${ruleCounter}" min="0" required>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="edit_point_weight_${ruleCounter}">Point Weight</label>
                            <input type="number" name="point_weight[]" class="form-control" id="edit_point_weight_${ruleCounter}" min="0" step="0.01" required>
                        </div>
                        <div class="form-group col-sm-2">
                            <label for="edit_rule_status_${ruleCounter}">Status</label>
                            <select name="rule_status[]" class="form-control" id="edit_rule_status_${ruleCounter}" required>
                                <option value="enabled">Enabled</option>
                                <option value="disabled">Disabled</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm remove-rule-btn">
                        <i class="fas fa-trash"></i> Remove Rule
                    </button>
                </div>
            `;
            $('#editRulesContainer').append(ruleHtml);
        }

        $(document).on('submit', '#editLoyaltyProgramForm', function(event) {
            event.preventDefault();
            processingBtn('#editLoyaltyProgramForm', '#btnEditSave', 'loading');

            let description = $('<div />').html($('#editLoyaltyProgramDescription').summernote('code'));
            let empty = description.text().trim().replace(/ \r\n\t/g, '') === '';

            if ($('#editLoyaltyProgramDescription').summernote('isEmpty')) {
                $('#editLoyaltyProgramDescription').val('');
            } else if (empty) {
                displayErrorMessage('Description field must not contain only white space.');
                processingBtn('#editLoyaltyProgramForm', '#btnEditSave', 'reset');
                return false;
            }

            $.ajax({
                url: loyaltyProgramUpdateUrl,
                type: 'put',
                data: $(this).serialize(),
                success: function(result) {
                    if (result.success) {
                        displaySuccessMessage(result.message);
                        window.location.href = loyaltyProgramUrl;
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#editLoyaltyProgramForm', '#btnEditSave');
                },
            });
        });
    </script>
@endsection
