<div class="container-fluid">
    <div class="row">
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('company_name', __('messages.customer.company_name') . ':') }}</strong><span class="required">*</span>
            <p style="color: #555;">{{ $supplier->company_name }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('vat_number', __('messages.customer.vat_number') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->vat_number }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('website', __('messages.customer.website') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->website }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('phone', __('messages.customer.phone') . ':') }}</strong><br>
            <p style="color: #555;">{{ $supplier->phone }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('currency', __('messages.customer.currency') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->currency ? $data['currencies'][$supplier->currency] : '' }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('country', __('messages.customer.country') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->country ? $data['countries'][$supplier->country] : '' }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('default_language', __('messages.customer.default_language') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->default_language ? $data['languages'][$supplier->default_language] : '' }}</p>
        </div>
        {{-- <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('groups', __('messages.customer.groups') . ':') }}</strong>
            <p style="color: #555;">
                @foreach($supplierGroups as $group)
                    {{ $data['supplierGroups'][$group->group_id] }}{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </p>
        </div> --}}
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('street', __('messages.customer.street') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->street }}</p>
        </div>
        {{-- <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('city', __('messages.customer.city') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->city }}</p>
        </div> --}}
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('zip', __('messages.customer.zip_code') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->zip }}</p>
        </div>
        {{-- <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('state', __('messages.customer.state') . ':') }}</strong>
            <p style="color: #555;">{{ $supplier->state }}</p>
        </div> --}}
    </div>
</div>
