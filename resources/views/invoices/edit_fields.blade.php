<input type="hidden" id="hdnInvoiceId" value="{{ $invoice->id }}">
<div class="card-body">
    <div class="alert alert-danger d-none" id="validationErrorsBox"></div>

    <div class="row">
        <div class="form-group  col-md-2 col-sm-12">
            {{ Form::label('admin_note', 'Branch') }}<span class="required">*</span>
            {{ Form::select('branch_id', $usersBranches ?? [], $invoice->branch_id ?? null, [
                'class' => 'form-control',
                'required',
                'id' => 'branchSelect',
                'placeholder' => 'Select Branch',
                'style' => "pointer-events: none;
                                                                                        background-color: #e9ecef;",
            ]) }}
        </div>
        {{-- <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('title', __('messages.invoice.title') . ':') }}<span class="required">*</span>
            {{ Form::text('title', isset($invoice->title) ? $invoice->title : null, ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'autofocus', 'placeholder' => __('messages.invoice.title')]) }}
        </div> --}}
        <input type="hidden" name="title" value=" ">
        <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('invoice_no', __('messages.invoice.invoice_number') . ':') }}<span class="required">*</span>
            {{ Form::text('invoice_number', $invoice->invoice_number ?? null, ['class' => 'form-control', 'required', 'id' => 'invoiceNumber', 'placeholder' => __('messages.invoice.invoice_number')]) }}
        </div>
        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('customer', __('messages.invoice.customer') . ':') }}<span class="required">*</span>
            {{ Form::select('customer_id', $data['customers'], isset($invoice->customer_id) ? $invoice->customer_id : null, ['class' => 'form-control', 'required', 'id' => 'customerSelectBox', 'placeholder' => __('messages.placeholder.select_customer')]) }}
        </div>
        <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('invoice_date', __('messages.invoice.invoice_date') . ':') }} <span class="required">*</span>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                {{ Form::text('invoice_date', isset($invoice->invoice_date) ? date('Y-m-d', strtotime($invoice->invoice_date)) : null, ['class' => 'form-control invoiceDate', 'required', 'autocomplete' => 'off', 'placeholder' => __('messages.invoice.invoice_date')]) }}
            </div>
        </div>
         <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('due_date', 'Invoice Month') }}
            {{ Form::month('invoice_month', $invoice->invoice_month ?? null, ['class' => 'form-control ', 'autocomplete' => 'off']) }}
        </div>
        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('due_date', __('messages.invoice.due_date') . ':') }}
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
                {{ Form::text('due_date', isset($invoice->due_date) ? date('Y-m-d', strtotime($invoice->due_date)) : null, ['class' => 'form-control invoiceDueDate', 'autocomplete' => 'off', 'placeholder' => __('messages.invoice.due_date')]) }}
            </div>
        </div>
        {{-- <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('tag', __('messages.tags') . ':') }}
            <div class="input-group">
                {{ Form::select('tags[]', $data['tags'], $invoice->tags->pluck('id'), ['class' => 'form-control', 'id' => 'tagId', 'autocomplete' => 'off', 'multiple' => 'multiple']) }}
                <div class="input-group-append plus-icon-height">
                    <div class="input-group-text">
                        <a href="#" data-toggle="modal" data-target="#addCommonTagModal"><i
                                class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('payment_modes', __('messages.invoice.allowed_payment_modes_for_this_invoice') . ':') }}
            <span class="required">*</span>
            <div class="input-group">
                {{ Form::select('payment_modes[]', $data['paymentModes'], $invoice->paymentModes->pluck('id'), ['class' => 'form-control', 'id' => 'paymentMode', 'required', 'autocomplete' => 'off', 'multiple' => 'multiple']) }}
                <div class="input-group-append plus-icon-height">
                    <div class="input-group-text">
                        <a href="#" data-toggle="modal" data-target="#addCommonPaymentModeModal"><i
                                class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('currency', __('messages.customer.currency') . ':') }}<span class="required">*</span>
            <select id="invoiceCurrencyId" data-show-content="true" class="form-control currency-select-box"
                name="currency" required>
                <option value="0" disabled="true" {{ isset($invoice->currency) ? '' : 'selected' }}>
                    {{ __('messages.placeholder.select_currency') }}
                </option>
                @foreach ($data['currencies'] as $key => $currency)
                    <option value="{{ $key }}"
                        {{ (isset($invoice->currency) ? $invoice->currency : null) == $key ? 'selected' : '' }}>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('sale_agent', __('messages.invoice.sale_agent') . ':') }}
            {{ Form::select('sales_agent_id', $data['saleAgents'], isset($invoice->sales_agent_id) ? $invoice->sales_agent_id : null, ['class' => 'form-control', 'id' => 'salesAgentId', 'placeholder' => __('messages.placeholder.select_sale_agent')]) }}
        </div> --}}
        {{-- <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('discount_type', __('messages.invoice.discount_type') . ':') }}<span
                class="required">*</span>
            {{ Form::select('discount_type', $data['discountType'], isset($invoice->discount_type) ? $invoice->discount_type : null, ['class' => 'form-control', 'id' => 'discountTypeSelect', 'placeholder', 'require']) }}
        </div> --}}
        {{-- <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('hsn_tax', __('messages.invoice.hsn_tax') . ':') }}
            {{ Form::text('hsn_tax', isset($invoice->hsn_tax) ? $invoice->hsn_tax : null, ['class' => 'form-control', 'placeholder' => __('messages.invoice.hsn_tax')]) }}
        </div> --}}
        <div class="form-group col-lg-4 col-md-6 col-sm-12">
            {{ Form::label('customer', __('messages.invoice.project') . ':') }}<span class="required">*</span>
            {{ Form::select('project_id', $projects ? $projects->pluck('project_name', 'id') : null, $invoice->project_id ?? null, ['class' => 'form-control', 'required', 'id' => 'projectSelect', 'placeholder' => __('messages.placeholder.select_project')]) }}
        </div>
        <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('customer', __('messages.project.po_number') . ':') }}
            {{ Form::text('po_number', $invoice->project->po_number ?? null, ['class' => 'form-control', 'required', 'id' => 'po_number', 'disabled']) }}
        </div>
        <div class="form-group col-md-2 col-sm-12">
            {{ Form::label('vendor_code', __('messages.customer.vendor_code')) }}
            {{ Form::text('vendor_code', $invoice->vendor_code ?? '', ['class' => 'form-control', 'id' => 'vendor_code', 'autocomplete' => 'off', 'readonly']) }}
        </div>



        <div class="form-group col-lg-2 col-md-4 col-sm-12">
            <a href="#" data-toggle="modal" data-target="#addModal" class="mr-3 addressModalIcon"><i
                    class="fa fa-edit"></i></a>
            {{ Form::label('bill_to', __('messages.invoice.bill_to') . ':') }}
            <div id="bill_to" class="ml-5">
                _ _ _ _ _ _
            </div>
        </div>
        {{-- <div class="form-group col-lg-2 col-md-6 col-sm-12">
            {{ Form::label('ship_to', __('messages.invoice.ship_to') . ':') }}
            <div id="ship_to">
                _ _ _ _ _ _
            </div>
        </div> --}}
        {{-- <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('admin_note', __('messages.invoice.admin_note') . ':') }}
            {{ Form::textarea('admin_text', isset($invoice->admin_text) ? nl2br(e($invoice->admin_text)) : null, ['class' => 'form-control summernote-simple', 'id' => 'editAdminNote']) }}
        </div> --}}
    </div>

    <br>
    @include('invoices.edit_items')
    <hr />
    <br />

    {{-- <div class="row">
        <div class="form-group col-lg-6 col-md-12 col-sm-12">
            {{ Form::label('', 'Services' . ':') }}
            <div class="input-group">
                {{ Form::select('item', $data['items'], null, ['class' => 'form-control', 'id' => 'addItemSelectBox']) }}
            </div>
        </div>
        <div
            class="form-group col-lg-6 col-md-12 col-sm-12 showQuantityAs d-flex align-items-center justify-content-end">
            <span class="font-weight-bold mr-2">{{ __('messages.invoice.show_quantity_as') . ':' }}</span>
            <div class="float-right showQuantityAs">
                <div class="custom-control custom-radio mr-3 d-inline-block">
                    <input type="radio" id="qty" name="unit" required value="1"
                        class="custom-control-input" data-quantity-for="qty"
                        {{ $invoice->unit == 1 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="qty">{{ __('messages.invoice.qty') }}</label>
                </div>
                <div class="custom-control custom-radio mr-3 d-inline-block">
                    <input type="radio" id="hours" name="unit" required value="2"
                        class="custom-control-input" data-quantity-for="hours"
                        {{ $invoice->unit == 2 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="hours">{{ __('messages.invoice.hours') }}</label>
                </div>
                <div class="custom-control custom-radio d-inline-block">
                    <input type="radio" id="qtyHours" name="unit" required value="3"
                        class="custom-control-input" data-quantity-for="qtyHours"
                        {{ $invoice->unit == 3 ? 'checked' : '' }}>
                    <label class="custom-control-label" for="qtyHours">{{ __('messages.invoice.qty_hours') }}</label>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 overflow-section">
            <table class="table table-responsive-sm table-responsive-md table-striped table-bordered" id="itemTable">
                <thead>
                    <tr>
                        <th>{{ __('messages.invoice.item') }}<span class="required">*</span></th>
                        <th>{{ __('messages.common.description') }}</th>
                        <th class="small-column"><span class="qtyHeader">{{ __('messages.invoice.qty') }}</span><span
                                class="required">*</span></th>
                        <th class="small-column">{{ __('messages.products.rate') }}<span class="required">*</span>
                        </th>
                        <th class="medium-column">{{ __('messages.products.tax') }}(<i
                                class="fas fa-percentage"></i>)</th>
                        <th class="small-column">{{ __('messages.invoice.amount') }}<span class="required">*</span>
                        </th>
                        <th class="button-column"><a href="#" id="itemAddBtn"></a>
                        </th>
                    </tr>
                </thead>
                <tbody class="items-container">
                    @foreach ($invoice->salesItems as $item)
                        <tr>
                            <td><input type="text" name="item[]" class="form-control item-name" required
                                    value="{{ html_entity_decode($item->item) }}"
                                    placeholder="{{ __('messages.invoice.item') }}"></td>
                            <td>
                                <textarea name="description[]" class="form-control item-description"
                                    placeholder="{{ __('messages.common.description') }}">{!! nl2br(e($item->description)) !!}</textarea>
                            </td>
                            <td><input type="text" name="quantity[]" class="form-control qty" required
                                    min="0" value="{{ $item->quantity }}"></td>
                            <td><input type="text" name="rate[]" class="form-control rate" required
                                    value="{{ $item->rate }}" placeholder="{{ __('messages.products.rate') }}">
                            </td>
                            <td class="">
                                {{ Form::select('tax[]', $data['taxesArr'], $item->taxes->pluck('id'), ['class' => 'form-control tax-rates']) }}
                            </td>
                            <td class="item-amount-width"> <span
                                    class="item-amount">{{ number_format($item->total) }}</span></td>
                            <td><a href="#" class="remove-invoice-item text-danger"><i
                                        class="far fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> --}}

    {{-- <div class="row">
        <div class="form-group col-lg-2 col-md-6 col-sm-12">
            {{ Form::label('sub_total', __('messages.invoice.sub_total') . ':') }}
            <p> <span id="subTotal">{{ $invoice->sub_total }}</span></p>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="fDiscount form-group">
                {{ Form::label('discount', __('messages.invoice.discount') . ':') }}
                ( <span class="footer-discount-numbers">{{ formatNumber($invoice->discount) }}</span>)
                <div class="input-group">
                    {{ Form::text('final_discount', $invoice->discount, ['class' => 'form-control footer-discount-input', 'placeholder' => __('messages.invoice.discount')]) }}
                    <div class="input-group-append">
                        @if (isset($invoice->discount_type) && $invoice->discount_type === 0)
                            <input type="hidden" name="discount_symbol" value="0">
                        @endif
                        <select class="input-group-text dropdown" id="footerDiscount" name="discount_symbol">
                            <div class="dropdown-menu">
                                <option value="1" class="dropdown-item"
                                    {{ isset($invoice->discount_symbol) && $invoice->discount_symbol == 1 ? 'selected' : '' }}>
                                    %
                                </option>
                                <option value="2" class="dropdown-item"
                                    {{ isset($invoice->discount_symbol) && $invoice->discount_symbol == 2 ? 'selected' : '' }}>
                                    {{ __('messages.invoice.fixed') }}</option>
                            </div>
                        </select>
                    </div>
                </div>
            </div>
            <table id="taxesListTable" class="w-100">
                @foreach ($invoice->salesTaxes as $tax)
                    <tr>
                        <td colspan="2" class="font-weight-bold tax-value">{{ $tax->tax }}%</td>
                        <td class="footer-numbers footer-tax-numbers">{{ $tax->amount }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12">
            {{ Form::label('adjustment', __('messages.invoice.adjustment') . ':') }}
            ( <span class="adjustment-numbers">{{ number_format($invoice->adjustment) }}</span>)
            {{ Form::number('adjustment', $invoice->adjustment, ['class' => 'form-control', 'id' => 'adjustment', 'placeholder' => __('messages.invoice.adjustment')]) }}
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12">
            {{ Form::label('total', __('messages.invoice.total') . ':') }}
            <p> <span class="total-numbers">{{ number_format($invoice->total_amount) }}</span></p>
        </div>
    </div> --}}

    <div class="row float-right">
        {{-- <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('client_note', __('messages.invoice.client_note') . ':') }}
            {{ Form::textarea('client_note', isset($invoice->client_note) ? nl2br(e($invoice->client_note)) : null, ['class' => 'form-control summernote-simple', 'id' => 'editClientNote']) }}
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            {{ Form::label('terms_conditions', __('messages.invoice.terms_conditions') . ':') }}
            {{ Form::textarea('term_conditions', isset($invoice->term_conditions) ? nl2br(e($invoice->term_conditions)) : null, ['class' => 'form-control summernote-simple', 'id' => 'editTermAndConditions']) }}
        </div> --}}
        <div class="form-group col-md-2 mr-2">
            <div class="btn-group dropup open mb-3">
                {{-- <a href="{{ url()->previous() }}"
                    class="btn btnSecondary text-white mr-2">{{ __('messages.common.cancel') }}</a> --}}
                {{ Form::button('Submit', ['class' => 'btn btn-primary', 'id' => '', 'style' => 'line-height:31px;']) }}
                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-left width200">
                    <li>
                        <a href="#" class="dropdown-item" id="editSaveAsDraft"
                            data-status="0">{{ __('messages.invoice.save_as_draft') }}</a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item" id="editSaveSend"
                            data-status="1">{{ __('messages.invoice.save_and_send') }}</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
