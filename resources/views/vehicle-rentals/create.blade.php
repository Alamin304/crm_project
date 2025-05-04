@extends('layouts.app')
@section('title')
    {{ __('messages.vehicle-rentals.add') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.vehicle-rentals.add') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('vehicle-rentals.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.common.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">

                <div class="modal-content">

                    {{ Form::open(['id' => 'addVehicleRentalForm', 'enctype' => 'multipart/form-data']) }}
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                {{ Form::label('rental_number', 'Rental Number') }}<span class="required">*</span>
                                {{ Form::text('rental_number', $nextNumber ?? 0, ['class' => 'form-control', 'required', 'id' => 'rentalNumber', 'readonly']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('vehicle_plate_number', 'ID Number') }}<span class="required">*</span>
                                {{ Form::text('plate_number', null, ['class' => 'form-control', 'required', 'id' => 'vehiclePlateNumber']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('vehicle_name', ' Name') }}<span class="required">*</span>
                                {{ Form::text('name', null, ['class' => 'form-control', 'required', 'id' => 'vehicleName']) }}
                            </div>



                            <div class="form-group col-md-6">
                                {{ Form::label('vehicle_type', 'Installment payment Schedule') }}<span class="required">*</span>
                                {{ Form::select('type', $types ?? [], null, ['class' => 'form-control', 'required', 'id' => 'vehicleType', 'placeholder' => 'Select Installment payment Schedule']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('amount', 'Amount') }}<span class="required">*</span>
                                {{ Form::number('amount', null, ['class' => 'form-control', 'required', 'id' => 'amount']) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('agreement_date', 'Agreement Type') }}<span class="required">*</span>
                                {{ Form::select('agreement_type', ['One-time' => 'One-time', 'Installment' => 'Installment'], null, [
                                    'class' => 'form-control',
                                    'id' => 'agreement_type',
                                    'placeholder' => __('Select Agreement Type'),
                                ]) }}
                            </div>
                            <div class="form-group col-md-6">
                                {{ Form::label('agreement_date', 'Agreement Date') }}<span class="required">*</span>
                                {{ Form::date('agreement_date', null, ['class' => 'form-control', 'required', 'id' => 'agreementDate']) }}
                            </div>

                            <div class="form-group col-md-6">
                                {{ Form::label('expiry_date', 'Expiry Date') }}<span class="required">*</span>
                                {{ Form::date('expiry_date', null, ['class' => 'form-control', 'required', 'id' => 'expiryDate']) }}
                            </div>
                            <div class="form-group col-md-6 ">
                                {{ Form::label('notification_date', 'Notification Days') }}
                                {{ Form::number('notification_days', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group col-md-6 d-none">
                                {{ Form::label('notification_date', 'Notification Date') }}
                                {{ Form::number('notification_date', null, ['class' => 'form-control', 'step' => 'any']) }}
                            </div>

                            <div class="form-group col-md-12">
                                {{ Form::label('vehicle_description', 'Description') }}
                                {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'vehicleDescription']) }}
                            </div>
                        </div>

                        <div class="text-right mr-3">
                            {{ Form::button('Submit', ['type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnSave', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                        </div>
                    </div>
                    {{ Form::close() }}


                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>

    <!-- AJAX script -->
    <script type="text/javascript">
        let assetCreateUrl = route('vehicle-rentals.store');
        let assetUrl = route('vehicle-rentals.index') + '/';

        $(document).on('submit', '#addVehicleRentalForm', function(event) {
            event.preventDefault();
            processingBtn('#addVehicleRentalForm', '#btnSave', 'loading');
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: assetCreateUrl, // Update with your actual route
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btnSave').attr('disabled', true).html(
                        "<span class='spinner-border spinner-border-sm'></span> Processing..."
                    );
                },
                success: function(response) {
                    if (response.success) {
                        displaySuccessMessage(response.message);
                        $('#addVehicleRentalForm')[0].reset();
                        $('#createDescription').val('');
                        $('#createDescription').summernote('code', '');
                        const url = route('vehicle-rentals.index', );
                        window.location.href = url;
                    }
                },
                error: function(result) {
                    displayErrorMessage(result.responseJSON.message);
                },
                complete: function() {
                    processingBtn('#addVehicleRentalForm', '#btnSave');
                },
            });
        });
    </script>
@endsection
