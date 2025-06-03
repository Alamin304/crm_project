<form id="currency-rates-form" action="{{ route('configuration.currency-rates-settings.update') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="maximum_number">Maximum Number</label>
                <input type="number" class="form-control" id="maximum_number" name="maximum_number"
                    value="{{ $currencyRatesSettings->maximum_number }}" required min="1">
            </div>

            <div class="form-group">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="automatic_get_currency_rate"
                        name="automatic_get_currency_rate" value="1"
                        {{ $currencyRatesSettings->automatic_get_currency_rate ? 'checked' : '' }}>
                    <label class="custom-control-label" for="automatic_get_currency_rate">Automatic Get Currency
                        Rate</label>
                </div>
            </div>
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
</form>
