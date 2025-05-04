<div class="container-fluid" >
    <div class="row">
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('company_name', __('messages.suppliers.name') . ':') }}<span class="required">*</span>
            {{ Form::text('company_name', $supplier->company_name, ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'autofocus' => true, 'placeholder' => __('messages.suppliers.name')]) }}
        </div>
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('vat_number', __('messages.customer.vat_number') . ':') }}
            {{ Form::text('vat_number', $supplier->vat_number, ['class' => 'form-control', 'minLength' => '4', 'maxLength' => '15', 'autocomplete' => 'off', 'placeholder' => __('messages.customer.vat_number')]) }}
        </div>
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('website', __('messages.customer.website') . ':') }}
            {{ Form::url('website', $supplier->website, ['class' => 'form-control', 'id' => 'website', 'autocomplete' => 'off', 'placeholder' => __('messages.customer.website')]) }}
        </div>
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('phone', __('messages.customer.phone') . ':') }}<br>
            {{ Form::tel('phone', $supplier->phone, ['class' => 'form-control', 'id' => 'phoneNumber', 'placeholder' => __('messages.customer.phone')]) }}
            {{ Form::hidden('prefix_code', old('prefix_code'), ['id' => 'prefix_code']) }}
            <span id="valid-msg" class="hide">{{ __('messages.placeholder.valid_number') }}</span>
            <span id="error-msg" class="hide"></span>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('currency', __('messages.customer.currency') . ':') }}
            <select id="currencyId" data-show-content="true" class="form-control" name="currency">
                <option value="0" disabled="true" {{ !isset($supplier->currency) ? 'selected' : '' }}>
                    {{ __('messages.placeholder.select_currency') }}
                </option>
                @foreach ($data['currencies'] as $key => $currency)
                    <option value="{{ $key }}"
                        {{ isset($supplier->currency) && $supplier->currency == $key ? 'selected' : '' }}>
                        {{ $currency }}
                    </option>
                @endforeach
            </select>

        </div>
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('country', __('messages.customer.country') . ':') }}
            {{ Form::select('country', $data['countries'], isset($supplier) ? $supplier->country : null, [
                'id' => 'countryId',
                'class' => 'form-control',
                'placeholder' => __('messages.placeholder.select_country'),
            ]) }}
        </div>
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('default_language', __('messages.customer.default_language') . ':') }}
            {{ Form::select('default_language', $data['languages'], isset($supplier) ? $supplier->default_language : null, [
                'id' => 'languageId',
                'class' => 'form-control',
                'placeholder' => __('messages.placeholder.select_language'),
            ]) }}
        </div>

        {{-- <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('groups', __('messages.customer.groups') . ':') }}
            <div class="input-group">
                {{ Form::select('groups[]', $data['supplierGroups'], isset($supplierGroups) ? $supplierGroups->pluck('group_id')->toArray() : [], ['id' => 'editgroupId', 'class' => 'form-control', 'multiple' => 'multiple']) }}
            </div>
        </div> --}}
        {{-- billing address --}}
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('street', __('messages.customer.street') . ':') }}
            {{ Form::text('street', $supplier->street ?? null, ['class' => 'form-control', 'id' => 'billingStreet', 'autocomplete' => 'off', 'placeholder' => __('messages.customer.street')]) }}
        </div>
        {{-- <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('city', __('messages.customer.city') . ':') }}
            {{ Form::text('city', $supplier->city ?? null, ['class' => 'form-control', 'id' => 'billingCity', 'autocomplete' => 'off', 'placeholder' => __('messages.customer.city')]) }}
        </div> --}}
        <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('zip', __('messages.customer.zip_code') . ':') }}
            {{ Form::text('zip', $supplier->zip ?? null, ['class' => 'form-control', 'id' => 'billingZip', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'autocomplete' => 'off', 'placeholder' => __('messages.customer.zip_code')]) }}
        </div>
        {{-- <div class="form-group col-md-6 col-sm-12">
            {{ Form::label('state', __('messages.customer.state') . ':') }}
            {{ Form::text('state', $supplier->state ?? null, ['class' => 'form-control', 'id' => 'billingState', 'autocomplete' => 'off', 'placeholder' => __('messages.customer.state')]) }}

        </div> --}}
    </div>
    <div class="form-group " style="float:right;">
        {{ Form::button(__('messages.common.submit'), ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnEditSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",'style'=>"line-height:30px;"]) }}
    </div>
</div>
