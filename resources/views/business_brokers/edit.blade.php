@extends('layouts.app')

@section('title')
    {{ __('messages.business_brokers.edit') }}
@endsection

@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/css/tempusdominus-bootstrap-4.min.css" />
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

        .current-image-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .attachment-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }

        .form-check-label {
            margin-left: 5px;
        }

        .attachment-link {
            display: block;
            margin-top: 5px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.business_brokers.edit') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('business_brokers.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.business_brokers.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['route' => ['business_brokers.update', $businessBroker->id], 'id' => 'editBusinessBrokerForm', 'files' => true, 'method' => 'put']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>

                            <div class="image-upload-wrapper">
                                @if ($businessBroker->profile_image)
                                    <img id="profileImagePreview"
                                        src="{{ asset('uploads/' . $businessBroker->profile_image) }}"
                                        class="profile-image-preview" alt="Profile Image">
                                    <div class="current-image-text">Current Image</div>
                                @else
                                    <img id="profileImagePreview" src="{{ asset('assets/img/default-user.png') }}"
                                        class="profile-image-preview" alt="Default Image">
                                @endif
                                <label for="profile_image" class="image-upload-label">
                                    <i class="fas fa-upload mr-1"></i> {{ __('Change Profile Image') }}
                                </label>
                                <input type="file" name="profile_image" id="profile_image" class="d-none"
                                    accept="image/*">
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('code', 'Broker Code:') }}
                                    {{ Form::text('code', $businessBroker->code, ['class' => 'form-control', 'id' => 'brokerCode', 'readonly' => 'readonly']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('owner_name', 'Broker Name:') }}<span class="required">*</span>
                                    {{ Form::text('owner_name', $businessBroker->owner_name, ['class' => 'form-control', 'required', 'id' => 'brokerName']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('email', 'Email:') }}
                                    {{ Form::email('email', $businessBroker->email, ['class' => 'form-control', 'id' => 'brokerEmail']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('phone_number', 'Phone Number:') }}<span class="required">*</span>
                                    {{ Form::tel('phone_number', $businessBroker->phone_number, ['class' => 'form-control', 'required', 'id' => 'brokerPhone']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('address', 'Address:') }}<span class="required">*</span>
                                {{ Form::text('address', $businessBroker->address, ['class' => 'form-control', 'required', 'id' => 'brokerAddress']) }}
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-4">
                                    {{ Form::label('city', 'City:') }}<span class="required">*</span>
                                    {{ Form::text('city', $businessBroker->city, ['class' => 'form-control', 'required', 'id' => 'brokerCity']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('state', 'State:') }}
                                    {{ Form::text('state', $businessBroker->state, ['class' => 'form-control', 'id' => 'brokerState']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('zip_code', 'Zip Code:') }}
                                    {{ Form::text('zip_code', $businessBroker->zip_code, ['class' => 'form-control', 'id' => 'brokerZipCode']) }}
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
                                        ],
                                        $businessBroker->country,
                                        ['class' => 'form-control select2', 'id' => 'brokerCountry', 'placeholder' => 'Select Country'],
                                    ) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('tax_id', 'Vat Number:') }}
                                    {{ Form::text('tax_id', $businessBroker->vat_number, ['class' => 'form-control', 'id' => 'brokerTaxId']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('website', 'Website:') }}
                                    {{ Form::url('website', $businessBroker->website, ['class' => 'form-control', 'id' => 'brokerWebsite']) }}
                                </div>

                                {{-- <div class="form-group col-md-6">
                                    {{ Form::label('license_number', 'License Number:') }}
                                    {{ Form::text('license_number', $businessBroker->license_number, ['class' => 'form-control', 'id' => 'brokerLicenseNumber']) }}
                                </div> --}}
                            </div>

                            <div class="form-group">
                                {{ Form::label('description', 'Description:') }}
                                {{ Form::textarea('description', $businessBroker->description, ['class' => 'form-control summernote-simple', 'id' => 'brokerDescription', 'rows' => 3]) }}
                            </div>
                                <div class="form-row-line mb-4">
                                <div class="form-group col-md-4">
                                    {{ Form::label('facebook_url', 'Facebook URL:') }}
                                    {{ Form::url('facebook_url', $businessBroker->facebook_url, ['class' => 'form-control', 'id' => 'agentFacebookUrl']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('whatsapp_url', 'WhatsApp URL:') }}
                                    {{ Form::url('whatsapp_url', $businessBroker->whatsapp_url, ['class' => 'form-control', 'id' => 'agentWhatsAppUrl']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('instagram_url', 'Instagram URL:') }}
                                    {{ Form::url('instagram_url', $businessBroker->instagram_url, ['class' => 'form-control', 'id' => 'agentInstagramUrl']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    <label>Verification Status:</label>
                                    <div class="form-check">
                                        {{ Form::radio('verification_status', 'verified', $businessBroker->verification_status == 'verified', ['class' => 'form-check-input', 'id' => 'verified']) }}
                                        {{ Form::label('verified', 'Verified', ['class' => 'form-check-label']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('verification_status', 'regular', $businessBroker->verification_status == 'regular', ['class' => 'form-check-input', 'id' => 'regular']) }}
                                        {{ Form::label('regular', 'Regular', ['class' => 'form-check-label']) }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Privacy:</label>
                                    <div class="form-check">
                                        {{ Form::radio('privacy', 'public', $businessBroker->privacy == 'public', ['class' => 'form-check-input', 'id' => 'public']) }}
                                        {{ Form::label('public', 'Public', ['class' => 'form-check-label']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('privacy', 'private', $businessBroker->privacy == 'private', ['class' => 'form-check-input', 'id' => 'private']) }}
                                        {{ Form::label('private', 'Private', ['class' => 'form-check-label']) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('documents', 'Documents:') }}
                                @if ($businessBroker->documents)
                                    <div>
                                        <a href="{{ asset('uploads/' . $businessBroker->documents) }}" target="_blank"
                                            class="attachment-link">
                                            <i class="fas fa-file-download"></i> Download Current Document
                                        </a>
                                    </div>
                                @endif
                                {{ Form::file('documents', ['class' => 'form-control-file', 'id' => 'brokerDocuments']) }}
                                <small class="form-text text-muted">Upload PDF, DOC, or image files (max 5MB)</small>
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
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Initialize Summernote
            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            // Profile image preview
            $('#profile_image').change(function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profileImagePreview').attr('src', e.target.result);
                        $('.current-image-text').text('New Image');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Form submission
            $(document).on('submit', '#editBusinessBrokerForm', function(e) {
                e.preventDefault();
                processingBtn('#editBusinessBrokerForm', '#btnSave', 'loading');

                let formData = new FormData(this);
                let id = {{ $businessBroker->id }};

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('business_brokers.index') }}";
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#editBusinessBrokerForm', '#btnSave');
                    }
                });
            });
        });
    </script>
@endsection
