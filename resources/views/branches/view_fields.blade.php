<div class="container-fluid">
    <div class="row">
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('company_name', __('messages.branches.company')) }}</strong>
            <p style="color: #555;">{{ $branch->company_name }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('company_name', __('messages.branches.branch_name')) }}</strong>
            <p style="color: #555;">{{ $branch->name }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('vat_number', __('messages.customer.vat_number')) }}</strong>
            <p style="color: #555;">{{ $branch->vat_number }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('website', __('messages.customer.website')) }}</strong>
            <p style="color: #555;">{{ $branch->website }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('phone', __('messages.customer.phone')) }}</strong>
            <p style="color: #555;">{{ $branch->phone }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('currency', __('messages.customer.currency')) }}</strong>
            <p style="color: #555;">{{ $branch->currency->name }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('country', __('messages.customer.country')) }}</strong>
            <p style="color: #555;">{{ $branch->country->name }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('city', __('messages.customer.city')) }}</strong>
            <p style="color: #555;">{{ $branch->city }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('state', __('messages.customer.state')) }}</strong>
            <p style="color: #555;">{{ $branch->state }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('zip_code', __('messages.customer.zip_code')) }}</strong>
            <p style="color: #555;">{{ $branch->zip_code }}</p>
        </div>
          <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('zip_code', __('messages.banks.name')) }}</strong>
            <p style="color: #555;">{{ $branch->bank?->name??'' }}</p>
        </div>
        <div class="form-group col-md-6 col-sm-12">
            <strong>{{ Form::label('address', __('messages.customer.address')) }}</strong>
            <div style="color: #555;"> {!! $branch->address !!}</div>
        </div>
    </div>
</div>
