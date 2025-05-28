@extends('layouts.app')

@section('title')
    {{ __('messages.property_owners.add') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" />
    <style>
        .form-row-line {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        .form-row-line .form-group {
            padding-right: 15px;
            padding-left: 15px;
            flex: 1 0 0%;
            max-width: 100%;
        }

        .profile-image-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
            margin-bottom: 15px;
            display: block;
        }

        .image-upload-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .image-upload-label {
            cursor: pointer;
            display: inline-block;
            padding: 8px 15px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .image-upload-label:hover {
            background: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.property_owners.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('property_owners.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.property_owners.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['route' => 'property_owners.store', 'id' => 'addPropertyOwnerForm', 'files' => true]) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>

                            <div class="image-upload-wrapper">
                                <img id="profileImagePreview" src="{{ asset('assets/img/default-user.png') }}" class="profile-image-preview" alt="Profile Image">
                                <label for="profile_image" class="image-upload-label">
                                    <i class="fas fa-upload mr-1"></i> {{ __('Upload Profile Image') }}
                                </label>
                                <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('code', 'Owner Code:') }}
                                    {{ Form::text('code', null, ['class' => 'form-control', 'id' => 'ownerCode', 'readonly' => 'readonly']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('owner_name', 'Owner Name:') }}<span class="required">*</span>
                                    {{ Form::text('owner_name', null, ['class' => 'form-control', 'required', 'id' => 'ownerName']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('email', 'Email:') }}
                                    {{ Form::email('email', null, ['class' => 'form-control', 'id' => 'ownerEmail']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('phone_number', 'Phone Number:') }}<span class="required">*</span>
                                    {{ Form::tel('phone_number', null, ['class' => 'form-control', 'required', 'id' => 'ownerPhone']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('address', 'Address:') }}<span class="required">*</span>
                                {{ Form::text('address', null, ['class' => 'form-control', 'required', 'id' => 'ownerAddress']) }}
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-4">
                                    {{ Form::label('city', 'City:') }}<span class="required">*</span>
                                    {{ Form::text('city', null, ['class' => 'form-control', 'required', 'id' => 'ownerCity']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('state', 'State:') }}
                                    {{ Form::text('state', null, ['class' => 'form-control', 'id' => 'ownerState']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('zip_code', 'Zip Code:') }}
                                    {{ Form::text('zip_code', null, ['class' => 'form-control', 'id' => 'ownerZipCode']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('country', 'Country:') }}
                                    {{ Form::select(
                                        'country',
                                        [
                                            'United States' => 'United States',
                                            'Canada' => 'Canada',
                                            'United Kingdom' => 'United Kingdom',
                                            'Australia' => 'Australia',
                                            // Add more countries as needed
                                        ],
                                        null,
                                        ['class' => 'form-control select2', 'id' => 'ownerCountry', 'placeholder' => 'Select Country'],
                                    ) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('vat_number', 'VAT Number:') }}
                                    {{ Form::text('vat_number', null, ['class' => 'form-control', 'id' => 'ownerVatNumber']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('website', 'Website:') }}
                                    {{ Form::url('website', null, ['class' => 'form-control', 'id' => 'ownerWebsite']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('facebook_url', 'Facebook URL:') }}
                                    {{ Form::url('facebook_url', null, ['class' => 'form-control', 'id' => 'ownerFacebookUrl']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('whatsapp_url', 'WhatsApp URL:') }}
                                    {{ Form::url('whatsapp_url', null, ['class' => 'form-control', 'id' => 'ownerWhatsAppUrl']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('instagram_url', 'Instagram URL:') }}
                                    {{ Form::url('instagram_url', null, ['class' => 'form-control', 'id' => 'ownerInstagramUrl']) }}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Generate owner code
            function generateOwnerCode() {
                const timestamp = new Date().getTime().toString().slice(-5);
                const randomNum = Math.floor(Math.random() * 9000) + 1000;
                $('#ownerCode').val(`OW#${timestamp}${randomNum}`);
            }

            generateOwnerCode();

            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Profile image preview
            $('#profile_image').change(function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profileImagePreview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Form submission
            $(document).on('submit', '#addPropertyOwnerForm', function(e) {
                e.preventDefault();
                processingBtn('#addPropertyOwnerForm', '#btnSave', 'loading');

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('property_owners.index') }}";
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#addPropertyOwnerForm', '#btnSave');
                    }
                });
            });
        });
    </script>
@endsection
