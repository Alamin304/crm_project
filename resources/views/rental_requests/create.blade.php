@extends('layouts.app')

@section('title')
    {{ __('Create Rental Request') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
    <style>
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .form-group {
            padding-right: 15px;
            padding-left: 15px;
            flex: 1 0 0%;
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .address-btn {
            cursor: pointer;
            color: #007bff;
        }

        .address-btn:hover {
            text-decoration: underline;
        }

        .required:after {
            content: " *";
            color: red;
        }

        .note-editor.note-frame {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
             <h1>{{ __('Create Rental Request') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('rental_requests.index') }}" class="btn btn-primary form-btn">{{ __('List') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'rental_requests.store', 'id' => 'createRentalRequestForm']) }}
                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="validationErrorsBox"></div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('request_number', 'Request Number:') }}
                                {{ Form::text('request_number', null, ['class' => 'form-control', 'readonly']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('date_created', 'Date Created:') }}
                                {{ Form::text('date_created', now()->format('Y-m-d H:i:s'), ['class' => 'form-control', 'readonly']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('property_name', 'Property Name:') }}<span class="required"></span>
                                {{ Form::text('property_name', null, ['class' => 'form-control', 'required']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('customer', 'Customer:') }}<span class="required"></span>
                                {{ Form::text('customer', null, ['class' => 'form-control', 'required']) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                {{ Form::label('property_price', 'Property Price:') }}
                                {{ Form::text('property_price', null, ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('contract_amount', 'Contract Amount:') }}
                                {{ Form::text('contract_amount', null, ['class' => 'form-control']) }}
                            </div>

                            <div class="form-group col-md-4">
                                {{ Form::label('term', 'Term (months):') }}<span class="required"></span>
                                {{ Form::number('term', null, ['class' => 'form-control', 'required', 'min' => 1]) }}
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('start_date', 'Start Date:') }}<span class="required"></span>
                                {{ Form::text('start_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('end_date', 'End Date:') }}<span class="required"></span>
                                {{ Form::text('end_date', null, ['class' => 'form-control datepicker', 'required', 'autocomplete' => 'off']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                {{ Form::checkbox('inspected_property', 1, false, ['class' => 'form-check-input', 'id' => 'inspected_property']) }}
                                {{ Form::label('inspected_property', 'Property Inspected', ['class' => 'form-check-label']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('bill_to', 'Bill To Address:') }}
                            <div class="d-flex align-items-center">
                                <span id="billToDisplay">Not specified</span>
                                <button type="button" class="btn btn-sm btn-link ml-2" data-toggle="modal" data-target="#billToModal">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                            {{ Form::hidden('bill_to', null, ['id' => 'bill_to']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('ship_to', 'Ship To Address:') }}
                            <div class="d-flex align-items-center">
                                <span id="shipToDisplay">Not specified</span>
                                <button type="button" class="btn btn-sm btn-link ml-2" data-toggle="modal" data-target="#shipToModal">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                            {{ Form::hidden('ship_to', null, ['id' => 'ship_to']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('client_note', 'Client Note:') }}
                            {{ Form::textarea('client_note', null, ['class' => 'form-control summernote-simple', 'rows' => 3]) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('admin_note', 'Admin Note:') }}
                            {{ Form::textarea('admin_note', null, ['class' => 'form-control summernote-simple', 'rows' => 3]) }}
                        </div>

                        <div class="text-right">
                            {{ Form::button(__('Submit'), [
                                'type' => 'submit',
                                'class' => 'btn btn-primary',
                                'id' => 'btnSave',
                                'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing...",
                            ]) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>

    <!-- Bill To Address Modal -->
    <div class="modal fade" id="billToModal" tabindex="-1" role="dialog" aria-labelledby="billToModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="billToModalLabel">Bill To Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="billToForm">
                        <div class="form-group">
                            {{ Form::label('street', 'Street:') }}
                            {{ Form::text('street', null, ['class' => 'form-control', 'id' => 'billToStreet']) }}
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('city', 'City:') }}
                                {{ Form::text('city', null, ['class' => 'form-control', 'id' => 'billToCity']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('state', 'State:') }}
                                {{ Form::text('state', null, ['class' => 'form-control', 'id' => 'billToState']) }}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('zip_code', 'Zip Code:') }}
                                {{ Form::text('zip_code', null, ['class' => 'form-control', 'id' => 'billToZipCode']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('country', 'Country:') }}
                                {{ Form::text('country', null, ['class' => 'form-control', 'id' => 'billToCountry']) }}
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveBillTo">Save Address</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ship To Address Modal -->
    <div class="modal fade" id="shipToModal" tabindex="-1" role="dialog" aria-labelledby="shipToModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shipToModalLabel">Ship To Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="shipToForm">
                        <div class="form-group">
                            {{ Form::label('street', 'Street:') }}
                            {{ Form::text('street', null, ['class' => 'form-control', 'id' => 'shipToStreet']) }}
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('city', 'City:') }}
                                {{ Form::text('city', null, ['class' => 'form-control', 'id' => 'shipToCity']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('state', 'State:') }}
                                {{ Form::text('state', null, ['class' => 'form-control', 'id' => 'shipToState']) }}
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                {{ Form::label('zip_code', 'Zip Code:') }}
                                {{ Form::text('zip_code', null, ['class' => 'form-control', 'id' => 'shipToZipCode']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('country', 'Country:') }}
                                {{ Form::text('country', null, ['class' => 'form-control', 'id' => 'shipToCountry']) }}
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveShipTo">Save Address</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Generate request number
            function generateRequestNumber() {
                const timestamp = new Date().getTime().toString().slice(-5);
                const randomNum = Math.floor(Math.random() * 9000) + 1000;
                $('input[name="request_number"]').val(`REQ-${timestamp}${randomNum}`);
            }

            generateRequestNumber();

            // Initialize datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });

            // Initialize summernote
            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            // Handle bill to address
            $('#saveBillTo').click(function() {
                const billToData = {
                    street: $('#billToStreet').val(),
                    city: $('#billToCity').val(),
                    state: $('#billToState').val(),
                    zip_code: $('#billToZipCode').val(),
                    country: $('#billToCountry').val()
                };

                $('#bill_to').val(JSON.stringify(billToData));

                // Display the address
                const displayText = [];
                if (billToData.street) displayText.push(billToData.street);
                if (billToData.city) displayText.push(billToData.city);
                if (billToData.state) displayText.push(billToData.state);
                if (billToData.zip_code) displayText.push(billToData.zip_code);
                if (billToData.country) displayText.push(billToData.country);

                $('#billToDisplay').text(displayText.join(', ') || 'Not specified');
                $('#billToModal').modal('hide');
            });

            // Handle ship to address
            $('#saveShipTo').click(function() {
                const shipToData = {
                    street: $('#shipToStreet').val(),
                    city: $('#shipToCity').val(),
                    state: $('#shipToState').val(),
                    zip_code: $('#shipToZipCode').val(),
                    country: $('#shipToCountry').val()
                };

                $('#ship_to').val(JSON.stringify(shipToData));

                // Display the address
                const displayText = [];
                if (shipToData.street) displayText.push(shipToData.street);
                if (shipToData.city) displayText.push(shipToData.city);
                if (shipToData.state) displayText.push(shipToData.state);
                if (shipToData.zip_code) displayText.push(shipToData.zip_code);
                if (shipToData.country) displayText.push(shipToData.country);

                $('#shipToDisplay').text(displayText.join(', ') || 'Not specified');
                $('#shipToModal').modal('hide');
            });

            // Load existing addresses if editing
            @if(isset($rentalRequest))
                // For bill to
                @if($rentalRequest->bill_to)
                    const billToData = @json(json_decode($rentalRequest->bill_to, true));
                    $('#billToStreet').val(billToData.street || '');
                    $('#billToCity').val(billToData.city || '');
                    $('#billToState').val(billToData.state || '');
                    $('#billToZipCode').val(billToData.zip_code || '');
                    $('#billToCountry').val(billToData.country || '');

                    const billToDisplay = [];
                    if (billToData.street) billToDisplay.push(billToData.street);
                    if (billToData.city) billToDisplay.push(billToData.city);
                    if (billToData.state) billToDisplay.push(billToData.state);
                    if (billToData.zip_code) billToDisplay.push(billToData.zip_code);
                    if (billToData.country) billToDisplay.push(billToData.country);

                    $('#billToDisplay').text(billToDisplay.join(', '));
                @endif

                // For ship to
                @if($rentalRequest->ship_to)
                    const shipToData = @json(json_decode($rentalRequest->ship_to, true));
                    $('#shipToStreet').val(shipToData.street || '');
                    $('#shipToCity').val(shipToData.city || '');
                    $('#shipToState').val(shipToData.state || '');
                    $('#shipToZipCode').val(shipToData.zip_code || '');
                    $('#shipToCountry').val(shipToData.country || '');

                    const shipToDisplay = [];
                    if (shipToData.street) shipToDisplay.push(shipToData.street);
                    if (shipToData.city) shipToDisplay.push(shipToData.city);
                    if (shipToData.state) shipToDisplay.push(shipToData.state);
                    if (shipToData.zip_code) shipToDisplay.push(shipToData.zip_code);
                    if (shipToData.country) shipToDisplay.push(shipToData.country);

                    $('#shipToDisplay').text(shipToDisplay.join(', '));
                @endif
            @endif

            // Form submission
            $(document).on('submit', '#createRentalRequestForm', function(e) {
                e.preventDefault();
                processingBtn('#createRentalRequestForm', '#btnSave', 'loading');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('rental_requests.index') }}";
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                        if (result.responseJSON.errors) {
                            displayValidationErrors(result.responseJSON.errors);
                        }
                    },
                    complete: function() {
                        processingBtn('#createRentalRequestForm', '#btnSave');
                    }
                });
            });

            function displayValidationErrors(errors) {
                let html = '<ul>';
                $.each(errors, function(key, value) {
                    html += '<li>' + value[0] + '</li>';
                });
                html += '</ul>';

                $('#validationErrorsBox').html(html);
                $('#validationErrorsBox').removeClass('d-none');
            }
        });
    </script>
@endsection
