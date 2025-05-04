<div class="card-body">
    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
    <div class="row">
        <div class="form-group  col-md-2 col-sm-12">
            {{ Form::label('admin_note', 'Branch') }}<span class="required">*</span>
            {{ Form::select('branch_id', $usersBranches ?? [], $estimate->branch_id ?? null, ['class' => 'form-control select2', 'required', 'id' => 'branchSelect']) }}
        </div>
        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12"> --}}
        {{-- {{ Form::label('title', __('messages.estimate.title') ) }}<span class="required">*</span> --}}
        {{ Form::hidden('title', ' ', ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'autofocus']) }}
        {{-- </div> --}}
        <div class="form-group  col-md-2 col-sm-12">
            {{ Form::label('estimate_number', __('messages.estimate.estimate_number')) }}<span class="required">*</span>
            {{ Form::text('estimate_number', $nextNumber, ['class' => 'form-control', 'required', 'id' => 'estimateNumber', 'readonly']) }}
        </div>
        <div class="form-group  col-md-2 col-sm-12">
            {{ Form::label('estimate_date', __('messages.estimate.estimate_date')) }} <span class="required">*</span>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                {{ Form::text('estimate_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="form-group col-md-4 col-sm-12">
            <label for="customer">{{ __('messages.estimate.customer_name') }}</label><span class="required">*</span>
            <div class="input-group">
                <!-- Customer Name Input (70% width) -->
                <input type="text" id="customerNameInput" name="customer_name" class="form-control" required>

                <!-- Customer ID Dropdown (30% width) -->
                <div class="input-group-append">
                    {{ Form::select('customer_id', $data['customers'], null, ['class' => 'form-control', 'required', 'id' => 'customerSelectBox', 'placeholder' => 'Select Customer']) }}
                </div>
            </div>
        </div>
        <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('vendor_code', __('messages.customer.vendor_code')) }}
            {{ Form::text('vendor_code', null, ['class' => 'form-control', 'autocomplete' => 'off', 'readonly']) }}
        </div>
        <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('reference', __('messages.estimate.customer_ref')) }}
            {{ Form::text('reference', null, ['class' => 'form-control', 'autocomplete' => 'off', 'id' => 'vendor_code']) }}
        </div>

        <div class="form-group  col-md-2 col-sm-12">
            {{ Form::label('expiry_date', __('messages.estimate.expiry_date')) }}
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                {{ Form::text('estimate_expiry_date', null, ['class' => 'form-control datepicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('tag', __('messages.tags') ) }}
            <div class="input-group">
                {{ Form::select('tags[]', $data['tags'], null, ['class' => 'form-control', 'id' => 'tagId', 'autocomplete' => 'off', 'multiple' => 'multiple']) }}
                <div class="input-group-append plus-icon-height">
                    <div class="input-group-text">
                        <a href="#" data-toggle="modal" data-target="#addCommonTagModal"><i
                                class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="form-group  col-md-2 col-sm-12">
            {{ Form::label('currency', __('messages.customer.currency')) }}<span class="required">*</span>
            <select id="estimateCurrencyId" data-show-content="true" class="form-control currency-select-box"
                name="currency" required>
                <option value="0" disabled="true" selected="true">{{ __('messages.placeholder.select_currency') }}
                </option>
                @foreach ($data['currencies'] as $key => $currency)
                    <option value="{{ $key }}"
                        {{ $key == getCurrentCurrencyIndex(getCurrentCurrency()) ? 'selected' : '' }}>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('reference', __('messages.employees.email')) }}
            {{ Form::email('email', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
        </div>


        <div class="form-group  col-md-2 col-sm-12 ">
            <a href="#" data-toggle="modal" data-target="#addModal" class="mr-3 addressModalIcon"><i
                    class="fa fa-edit"></i></a>
            {{ Form::label('bill_to', __('messages.invoice.bill_to')) }}
            <div id="bill_to" class="ml-5">
                _ _ _ _ _ _
            </div>
        </div>
        {{-- <div class="form-group col-lg-2 col-md-4 col-sm-12">
            {{ Form::label('ship_to', __('messages.invoice.ship_to') ) }}
            <div id="ship_to">
                _ _ _ _ _ _
            </div>
        </div> --}}
        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('sale_agent', __('messages.credit_note.reference') ) }}
            {{ Form::select('sales_agent_id', $data['saleAgents'], null, ['class' => 'form-control sale-agent-select-box', 'id' => 'saleAgentId', 'placeholder' => __('messages.credit_note.reference')]) }}
        </div> --}}
        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('discount_type', __('messages.invoice.discount_type') ) }}<span
                class="required">*</span>
            {{ Form::select('discount_type', $data['discountType'], null, ['class' => 'form-control', 'id' => 'discountTypeSelect', 'required', 'placeholder' => __('messages.placeholder.select_discount_type')]) }}
        </div> --}}
        {{-- <div class="form-group col-lg-8 col-md-8 col-sm-12">
            {{ Form::label('address', __('messages.invoice.bill_to') ) }}
            {{ Form::text('address', isset($estimate->address) ? $estimate->address : null, ['class' => 'form-control']) }}
        </div> --}}

        <div class="form-group  col-md-2 col-sm-12">
            {{ Form::label('admin_note', 'Subject') }}<span class="required">*</span>
            {{ Form::text('admin_note', null, ['class' => 'form-control', 'required', 'maxlength' => 100]) }}
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('client_note', 'Message') }}
            {{ Form::textarea('client_note', isset($settings) ? $settings['client_note'] : null, ['class' => 'form-control ', 'style' => 'height:70px;']) }}
        </div>
    </div>


    @include('estimates.add_items')
    <br>


    <div class="row float-right pb-2">

        {{-- <a href="{{ url()->previous() }}"
            class="btn btnSecondary  text-white mr-3">{{ __('messages.common.cancel') }}</a> --}}
        <div class="btn-group dropup open">
            {{ Form::button('Submit', ['class' => 'btn btn-primary', 'style' => 'line-height:31px;']) }}
            {{-- <a href="#" class="btn btn-primary" id="saveAsDraft"
                data-status="0">{{ __('messages.common.save') }}</a> --}}
            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="true">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-left width200">
                <li>
                    <a href="#" class="dropdown-item" id="saveAsDraft"
                        data-status="0">{{ __('messages.estimate.save_as_draft') }}</a>
                </li>
                <li>
                    <a href="#" class="dropdown-item" id="saveAndSend"
                        data-status="1">{{ __('messages.estimate.save_and_send') }}</a>
                </li>
            </ul>
        </div>


    </div>
</div>
