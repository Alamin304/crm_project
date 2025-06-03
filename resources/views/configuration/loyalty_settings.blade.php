<form id="loyalty-settings-form" action="{{ route('configuration.loyalty-settings.update') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="enable_loyalty" name="enable_loyalty"
                        value="1" {{ $loyaltySettings->enable_loyalty ? 'checked' : '' }}>
                    <label class="custom-control-label" for="enable_loyalty">Enable loyalty</label>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="earn_points_from_redeemable"
                        name="earn_points_from_redeemable" value="1"
                        {{ $loyaltySettings->earn_points_from_redeemable ? 'checked' : '' }}>
                    <label class="custom-control-label" for="earn_points_from_redeemable">Earn points from redeemable
                        transactions</label>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="row mt-4">
        <div class="col-md-6">
            <div class="form-group">
                <label>The member tab(client area) will not be displayed to the following client groups</label>
                <select class="form-control select2-client-groups" name="hidden_client_groups[]" multiple="multiple">
                    @foreach ($clientGroups as $group)
                        <option value="{{ $group->id }}" {{ in_array($group->id, json_decode($loyaltySettings->hidden_client_groups ?? '[]')) ? 'selected' : '' }}>
                            {{ $group->name }}
                        </option>
                    @endforeach
                    @foreach ($clientGroups as $group)
                        <option value="{{ $group['id'] }}"
                            {{ in_array($group['id'], json_decode($loyaltySettings->hidden_client_groups ?? '[]')) ? 'selected' : '' }}>
                            {{ $group['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>The member tab(client area) will not be displayed to the following clients</label>
                <select class="form-control select2-clients" name="hidden_clients[]" multiple="multiple">
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}"
                            {{ in_array($client->id, json_decode($loyaltySettings->hidden_clients ?? '[]')) ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                    @foreach ($clientGroups as $group)
                        <option value="{{ $group['id'] }}"
                            {{ in_array($group['id'], json_decode($loyaltySettings->hidden_client_groups ?? '[]')) ? 'selected' : '' }}>
                            {{ $group['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div> --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="form-group">
                <label>The member tab (client area) will not be displayed to the following client groups</label>
                <select class="form-control select2-client-groups" name="hidden_client_groups[]" multiple="multiple">
                    <option value="1"
                        {{ in_array(1, json_decode($loyaltySettings->hidden_client_groups ?? '[]')) ? 'selected' : '' }}>
                        VIP Clients</option>
                    <option value="2"
                        {{ in_array(2, json_decode($loyaltySettings->hidden_client_groups ?? '[]')) ? 'selected' : '' }}>
                        Regular Clients</option>
                    <option value="3"
                        {{ in_array(3, json_decode($loyaltySettings->hidden_client_groups ?? '[]')) ? 'selected' : '' }}>
                        Trial Users</option>
                    <option value="4"
                        {{ in_array(4, json_decode($loyaltySettings->hidden_client_groups ?? '[]')) ? 'selected' : '' }}>
                        Corporate Accounts</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>The member tab (client area) will not be displayed to the following clients</label>
                <select class="form-control select2-clients" name="hidden_clients[]" multiple="multiple">
                    <option value="101"
                        {{ in_array(101, json_decode($loyaltySettings->hidden_clients ?? '[]')) ? 'selected' : '' }}>
                        John Doe</option>
                    <option value="102"
                        {{ in_array(102, json_decode($loyaltySettings->hidden_clients ?? '[]')) ? 'selected' : '' }}>
                        Jane Smith</option>
                    <option value="103"
                        {{ in_array(103, json_decode($loyaltySettings->hidden_clients ?? '[]')) ? 'selected' : '' }}>
                        Acme Corp</option>
                    <option value="104"
                        {{ in_array(104, json_decode($loyaltySettings->hidden_clients ?? '[]')) ? 'selected' : '' }}>
                        Bob Builder</option>
                </select>
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

<script>
    $(document).ready(function() {
        $('.select2-client-groups').select2({
            placeholder: "Select client groups"
        });

        $('.select2-clients').select2({
            placeholder: "Select clients"
        });
    });
</script>
