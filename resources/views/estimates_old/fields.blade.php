<div class="card-body">
    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
    <div class="row">

        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12"> --}}
        {{-- {{ Form::label('title', __('messages.estimate.title').':') }}<span class="required">*</span> --}}
        {{ Form::hidden('title', '""', ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'autofocus', 'placeholder' => __('messages.products.title')]) }}
        {{-- </div> --}}
        <div class="form-group col-lg-4 col-md-8 col-sm-12">
            {{ Form::label('customer_name', __('messages.estimate.customer_name') . ':') }}<span
                class="required">*</span>
            {{ Form::text('customer_name', null, ['class' => 'form-control', 'requierd', 'autocomplete' => 'off']) }}
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('reference', __('messages.estimate.reference') . ':') }}
            {{ Form::text('reference', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
        </div>
        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('estimate_number', __('messages.estimate.estimate_number') . ':') }}<span
                class="required">*</span>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        {{ __('messages.estimate.estimate_prefix') }}
                    </div>
                </div>
                {{ Form::text('estimate_number', generateUniqueEstimateNumber(), ['class' => 'form-control', 'required', 'id' => 'estimateNumber', 'readonly', 'placeholder' => __('messages.estimate.estimate_number')]) }}
            </div>
        </div>
        {{-- <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('customer', __('messages.invoice.customer') . ':') }}<span class="required">*</span>
            {{ Form::select('customer_id', $data['customers'], isset($customerId) ? $customerId : null, ['class' => 'form-control', 'required', 'id' => 'customerSelectBox', 'placeholder' => __('messages.placeholder.select_customer')]) }}
        </div> --}}
        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('estimate_date', __('messages.estimate.estimate_date') . ':') }} <span
                class="required">*</span>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                {{ Form::text('estimate_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
            </div>
        </div>

        <div class="form-group col-lg-4 col-md-8 col-sm-12">
            {{ Form::label('address', __('messages.estimate.mobile') . ':') }}
            {{ Form::text('mobile', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
        </div>
        <div class="form-group col-lg-4 col-md-8 col-sm-12">
            {{ Form::label('address', __('messages.company.address') . ':') }}
            {{ Form::text('address', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
        </div>
        <div class="form-group col-lg-4 col-md-8 col-sm-12">
            {{ Form::label('address', __('messages.estimate.email') . ':') }}
            {{ Form::email('email', null, ['class' => 'form-control', 'autocomplete' => 'off']) }}
        </div>

        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('expiry_date', __('messages.estimate.expiry_date') . ':') }}
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                {{ Form::text('estimate_expiry_date', null, ['class' => 'form-control due-datepicker', 'autocomplete' => 'off']) }}
            </div>
        </div>
        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('tag', __('messages.tags') . ':') }}
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
        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('currency', __('messages.customer.currency') . ':') }}<span class="required">*</span>
            <select id="estimateCurrencyId" data-show-content="true" class="form-control currency-select-box"
                name="currency" required>
                <option value="0" disabled="true" selected="true">{{ __('messages.placeholder.select_currency') }}
                </option>
                @foreach ($data['currencies'] as $key => $currency)
                    <option value="{{ $key }}"
                        {{ $key == getCurrentCurrencyIndex(getCurrentCurrency()) ? 'selected' : '' }}>
                        &#{{ getCurrencyIcon($key) }}&nbsp;&nbsp;&nbsp; {{ $currency }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        {{-- <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('sale_agent', __('messages.invoice.sale_agent') . ':') }}
            {{ Form::select('sales_agent_id', $data['saleAgents'], null, ['class' => 'form-control sale-agent-select-box', 'id' => 'saleAgentId', 'placeholder' => __('messages.placeholder.select_sale_agent')]) }}
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            {{ Form::label('discount_type', __('messages.invoice.discount_type') . ':') }}<span
                class="required">*</span>
            {{ Form::select('discount_type', $data['discountType'], null, ['class' => 'form-control', 'id' => 'discountTypeSelect', 'required', 'placeholder' => __('messages.placeholder.select_discount_type')]) }}
        </div> --}}

        {{-- <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('hsn_tax', __('messages.invoice.hsn_tax') . ':') }}
            {{ Form::text('hsn_tax', isset($estimate->hsn_tax) ? $estimate->hsn_tax : null, ['class' => 'form-control', 'placeholder' => __('messages.invoice.hsn_tax')]) }}
        </div> --}}
        {{-- <div class="form-group col-lg-2 col-md-4 col-sm-12">
            <a href="#" data-toggle="modal" data-target="#addModal" class="mr-3 addressModalIcon"><i
                    class="fa fa-edit"></i></a>
            {{ Form::label('bill_to', __('messages.invoice.bill_to') . ':') }}
            <div id="bill_to" class="ml-5">
                _ _ _ _ _ _
            </div>
        </div>
        <div class="form-group col-lg-2 col-md-4 col-sm-12">
            {{ Form::label('ship_to', __('messages.invoice.ship_to') . ':') }}
            <div id="ship_to">
                _ _ _ _ _ _
            </div>
        </div> --}}
        {{-- <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('admin_note', __('messages.invoice.admin_note') . ':') }}
            {{ Form::textarea('admin_note', isset($settings) ? $settings['admin_note'] : null, ['class' => 'form-control summernote-simple']) }}
        </div> --}}
    </div>

    <hr />
    <br />

    {{-- <div class="row">
        <div class="form-group col-lg-6 col-md-12 col-sm-12">
            <div class="input-group">
                {{ Form::select('item', $data['items'], null, ['class' => 'form-control', 'id' => 'addItemSelectBox', 'placeholder' => __('messages.placeholder.select_product')]) }}
            </div>
        </div>
        <div
            class="form-group col-lg-6 col-md-12 col-sm-12 showQuantityAs d-flex align-items-center justify-content-end">
            <span class="font-weight-bold mr-2">{{ __('messages.invoice.show_quantity_as') . ':' }}</span>
            <div class="float-right showQuantityAs">
                <div class="custom-control custom-radio mr-3 d-inline-block">
                    <input type="radio" id="qty" name="unit" required value="1"
                        class="custom-control-input" data-quantity-for="qty" >
                    <label class="custom-control-label" for="qty">{{ __('messages.invoice.qty') }}</label>
                </div>
                <div class="custom-control custom-radio mr-3 d-inline-block">
                    <input type="radio" id="hours" name="unit" required value="2"
                        class="custom-control-input" data-quantity-for="hours" checked>
                    <label class="custom-control-label" for="hours">{{ __('messages.invoice.hours') }}</label>
                </div>
                <div class="custom-control custom-radio d-inline-block">
                    <input type="radio" id="qtyHours" name="unit" required value="3"
                        class="custom-control-input" data-quantity-for="qtyHours">
                    <label class="custom-control-label" for="qtyHours">{{ __('messages.invoice.qty/hours') }}</label>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 overflow-section">
            <table class="table table-responsive-md table-responsive-md table-striped table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('messages.estimate.item') }}<span class="required">*</span></th>
                        {{-- <th class="small-column"><span class="qtyHeader">{{ __('messages.estimate.qty') }}</span><span
                                class="required">*</span></th> --}}
                        <th class="small-column">{{ __('messages.estimate.rate') }}<span class="required">*</span></th>
                        {{-- <th class="medium-column">{{ __('messages.estimate.taxes') }}(<i
                                class="fas fa-percentage"></i>)</th> --}}
                        {{-- <th class="small-column">{{ __('messages.estimate.amount') }}<span class="required">*</span>
                        </th> --}}
                        <th class="button-column">Remarks</th>
                        <th class="button-column">

                            <a id="EmployeeAddAddBtn"></a>

                        </th>
                    </tr>
                </thead>
                <tbody class="items-container">
                    <tr>
                        <td style="width: 40%;">
                            <div>
                                {{ Form::select('employee_id[]', $employees, null, [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'employee_select',
                                ]) }}
                            </div>
                        </td>

                        <td><input type="number"  min="0" name="rate[]" class="form-control "
                                required></td>
                        <td><input type="text" name="remarks[]" class="form-control rate"></td>
                        <td>

                            <a class='btn text-danger remove-invoice-item '><i class="far fa-trash-alt"></i></a>

                        </td>
                        {{-- <td class="">
                            {{ Form::select('tax[]', $data['taxesArr'], null, ['class' => 'form-control tax-rates']) }}
                        </td>
                        <td class="item-amount-width"> <span class="item-amount">0</span></td>
                        <td></td> --}}
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    {{-- <div class="row">
        <div class="form-group col-lg-2 col-md-6 col-sm-12">
            {{ Form::label('sub_total', __('messages.estimate.sub_total') . ':') }}
            <p> <span id="subTotal">0</span></p>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="fDiscount form-group">
                {{ Form::label('discount', __('messages.estimate.discount') . ':') }}
                ( <span class="footer-discount-numbers">0</span>)
                <div class="input-group">
                    {{ Form::text('final_discount', 0, ['class' => 'form-control footer-discount-input', 'placeholder' => __('messages.estimate.discount')]) }}
                    <div class="input-group-append">
                        <select class="input-group-text dropdown" id="footerDiscount" name="discount_symbol">
                            <div class="dropdown-menu">
                                <option value="1" class="dropdown-item">%</option>
                                <option value="2" class="dropdown-item">{{ __('messages.invoice.fixed') }}
                                </option>
                            </div>
                        </select>
                    </div>
                </div>
            </div>
            <table id="taxesListTable" class="w-100">
            </table>
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12">
            {{ Form::label('adjustment', __('messages.estimate.adjustment') . ':') }}
            ( <span class="adjustment-numbers">0</span>)
            {{ Form::number('adjustment', 0, ['class' => 'form-control', 'id' => 'adjustment', 'placeholder' => __('messages.estimate.adjustment')]) }}
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12">
            {{ Form::label('total', __('messages.estimate.total') . ':') }}
            <p> <span class="total-numbers">0</span></p>
        </div>
    </div> --}}

    <div class="row">

        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('terms_conditions', __('messages.estimate.terms_conditions') . ':') }}
            {{ Form::textarea('term_conditions', null, ['class' => 'form-control summernote-simple']) }}
        </div>
        <div class="form-group col-sm-12">
            {{-- <div class="btn-group dropup open"> --}}
            {{--                {{ Form::button('Save', ['class' => 'btn btn-primary']) }} --}}

            {{-- <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">
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
                </ul> --}}
            {{-- </div> --}}

            {{-- <a href="{{ url()->previous() }}"
                class="btn btn-secondary text-dark ml-3">{{ __('messages.common.cancel') }}</a> --}}
        </div>
    </div>
    <div class="row justify-content-end mr-1">
        <a href="#" class="btn btn-primary" id="saveAsDraft" data-status="0">{{ __('messages.common.save') }}</a>
    </div>
</div>
