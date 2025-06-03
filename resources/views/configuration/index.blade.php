@extends('layouts.app')
@section('title')
    {{ __('Configuration') }}
@endsection

@section('content')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="container-fluid">
    {{-- <div class="row mb-3">
        <div class="col-md-12">
            <h2 class="page-title">Configuration</h2>
        </div>
    </div> --}}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#loyalty-settings">Loyalty Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#membership-card-templates">Membership Card Templates</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#currency-rates">Currency Rates</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="loyalty-settings">
                            @include('configuration.loyalty_settings')
                        </div>
                        <div class="tab-pane fade" id="membership-card-templates">
                            @include('configuration.membership_card_templates.index')
                        </div>
                        <div class="tab-pane fade" id="currency-rates">
                            @include('configuration.currency_rates')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize select2 for client groups and clients
        $('.select2-client-groups').select2({
            placeholder: "Select client groups",
            allowClear: true
        });

        $('.select2-clients').select2({
            placeholder: "Select clients",
            allowClear: true
        });
    });

    $(document).ready(function() {
    // Loyalty Settings Form
    $('#loyalty-settings-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message || 'An error occurred');
            }
        });
    });

    // Currency Rates Form
    $('#currency-rates-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message || 'An error occurred');
            }
        });
    });
});
</script>
@endpush
