@extends('layouts.app')

@section('title')
    {{ __('messages.real_estate_agents.add') }}
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

        .attachment-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }

        .form-check-label {
            margin-left: 5px;
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.real_estate_agents.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('real_estate_agents.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.real_estate_agents.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['route' => 'real_estate_agents.store', 'id' => 'addRealEstateAgentForm', 'files' => true]) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>

                            <div class="image-upload-wrapper">
                                <img id="profileImagePreview" src="{{ asset('assets/img/default-user.png') }}"
                                    class="profile-image-preview" alt="Profile Image">
                                <label for="profile_image" class="image-upload-label">
                                    <i class="fas fa-upload mr-1"></i> {{ __('Upload Profile Image') }}
                                </label>
                                <input type="file" name="profile_image" id="profile_image" class="d-none"
                                    accept="image/*">
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('code', 'Agent Code:') }}
                                    {{ Form::text('code', null, ['class' => 'form-control', 'id' => 'agentCode', 'readonly' => 'readonly']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('owner_name', 'Agent Name:') }}<span class="required">*</span>
                                    {{ Form::text('owner_name', null, ['class' => 'form-control', 'required', 'id' => 'agentName']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('email', 'Email:') }}
                                    {{ Form::email('email', null, ['class' => 'form-control', 'id' => 'agentEmail']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('phone_number', 'Phone Number:') }}<span class="required">*</span>
                                    {{ Form::tel('phone_number', null, ['class' => 'form-control', 'required', 'id' => 'agentPhone']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('address', 'Address:') }}<span class="required">*</span>
                                {{ Form::text('address', null, ['class' => 'form-control', 'required', 'id' => 'agentAddress']) }}
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-4">
                                    {{ Form::label('city', 'City:') }}<span class="required">*</span>
                                    {{ Form::text('city', null, ['class' => 'form-control', 'required', 'id' => 'agentCity']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('state', 'State:') }}
                                    {{ Form::text('state', null, ['class' => 'form-control', 'id' => 'agentState']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('zip_code', 'Zip Code:') }}
                                    {{ Form::text('zip_code', null, ['class' => 'form-control', 'id' => 'agentZipCode']) }}
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
                                        null,
                                        ['class' => 'form-control select2', 'id' => 'agentCountry', 'placeholder' => 'Select Country'],
                                    ) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('vat_number', 'VAT Number:') }}
                                    {{ Form::text('vat_number', null, ['class' => 'form-control', 'id' => 'agentVatNumber']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('website', 'Website:') }}
                                    {{ Form::url('website', null, ['class' => 'form-control', 'id' => 'agentWebsite']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('plan', 'Plan:') }}
                                    {{ Form::text('plan', null, ['class' => 'form-control', 'id' => 'agentPlan']) }}
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-4">
                                    {{ Form::label('facebook_url', 'Facebook URL:') }}
                                    {{ Form::url('facebook_url', null, ['class' => 'form-control', 'id' => 'agentFacebookUrl']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('whatsapp_url', 'WhatsApp URL:') }}
                                    {{ Form::url('whatsapp_url', null, ['class' => 'form-control', 'id' => 'agentWhatsAppUrl']) }}
                                </div>

                                <div class="form-group col-md-4">
                                    {{ Form::label('instagram_url', 'Instagram URL:') }}
                                    {{ Form::url('instagram_url', null, ['class' => 'form-control', 'id' => 'agentInstagramUrl']) }}
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('information', 'Additional Information:') }}
                                {{ Form::textarea('information', null, ['class' => 'form-control summernote-simple', 'id' => 'agentInformation', 'rows' => 3]) }}
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    <label>Verification Status:</label>
                                    <div class="form-check">
                                        {{ Form::radio('verification_status', 'verified', false, ['class' => 'form-check-input', 'id' => 'verified']) }}
                                        {{ Form::label('verified', 'Verified', ['class' => 'form-check-label']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('verification_status', 'regular', true, ['class' => 'form-check-input', 'id' => 'regular']) }}
                                        {{ Form::label('regular', 'Regular', ['class' => 'form-check-label']) }}
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Privacy:</label>
                                    <div class="form-check">
                                        {{ Form::radio('privacy', 'public', true, ['class' => 'form-check-input', 'id' => 'public']) }}
                                        {{ Form::label('public', 'Public', ['class' => 'form-check-label']) }}
                                    </div>
                                    <div class="form-check">
                                        {{ Form::radio('privacy', 'private', false, ['class' => 'form-check-input', 'id' => 'private']) }}
                                        {{ Form::label('private', 'Private', ['class' => 'form-check-label']) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('attachment', 'Attachment:') }}
                                {{ Form::file('attachment', ['class' => 'form-control-file', 'id' => 'agentAttachment']) }}
                                <small class="form-text text-muted">Upload PDF, DOC, or image files (max 5MB)</small>
                                <img id="attachmentPreview" class="attachment-preview" alt="Attachment Preview">
                            </div>

                            {{-- <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    {{ Form::checkbox('is_active', 1, true, ['class' => 'custom-control-input', 'id' => 'agentIsActive']) }}
                                    {{ Form::label('agentIsActive', 'Active', ['class' => 'custom-control-label']) }}
                                </div>
                            </div> --}}

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
            // Generate agent code
            function generateAgentCode() {
                const timestamp = new Date().getTime().toString().slice(-5);
                const randomNum = Math.floor(Math.random() * 9000) + 1000;
                $('#agentCode').val(`REA#${timestamp}${randomNum}`);
            }

            generateAgentCode();

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
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Attachment preview
            $('#agentAttachment').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (file.type.match('image.*')) {
                            $('#attachmentPreview').attr('src', e.target.result).show();
                        } else {
                            $('#attachmentPreview').hide();
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Form submission
            $(document).on('submit', '#addRealEstateAgentForm', function(e) {
                e.preventDefault();
                processingBtn('#addRealEstateAgentForm', '#btnSave', 'loading');

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
                            window.location.href = "{{ route('real_estate_agents.index') }}";
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#addRealEstateAgentForm', '#btnSave');
                    }
                });
            });
        });
    </script>
@endsection
