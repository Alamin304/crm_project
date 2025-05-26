@extends('layouts.app')
@section('title')
    {{ __('messages.warranties.add') }}
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
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.warranties.add') }}</h1>
            <div class="section-header-breadcrumb float-right"></div>
            <div class="float-right">
                <a href="{{ route('warranties.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.warranties.list') }}
                </a>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="modal-content">
                        {{ Form::open(['id' => 'addWarrantyForm']) }}
                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-6">
                                    {{ Form::label('claim_code', 'Claim Code:') }}
                                    {{ Form::text('claim_code', null, ['class' => 'form-control', 'id' => 'claimCode', 'readonly' => 'readonly']) }}
                                </div>

                                <div class="form-group col-md-6">
                                    {{ Form::label('date_created', 'Date Created:') }}<span class="required">*</span>
                                    <div class="input-group date" id="warrantyDatePicker" data-target-input="nearest">
                                        {{ Form::text('date_created', now()->format('Y-m-d H:i'), ['class' => 'form-control datetimepicker-input', 'data-target' => '#warrantyDatePicker', 'required', 'autocomplete' => 'off']) }}
                                        <div class="input-group-append" data-target="#warrantyDatePicker"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row-line mb-4">
                                <div class="form-group col-md-3">
                                    {{ Form::label('customer', 'Customer:') }}<span class="required">*</span>
                                    {{ Form::select(
                                        'customer',
                                        [
                                            '1' => 'John Doe',
                                            '2' => 'Jane Smith',
                                            '3' => 'Acme Corporation',
                                            '4' => 'XYZ Enterprises',
                                        ],
                                        null,
                                        ['class' => 'form-control select2', 'required', 'id' => 'customer', 'placeholder' => 'Select Customer'],
                                    ) }}
                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('invoice', 'Invoice:') }}
                                    {{ Form::select(
                                        'invoice',
                                        [
                                            'INV-001' => 'INV-001',
                                            'INV-002' => 'INV-002',
                                            'INV-003' => 'INV-003',
                                            'INV-004' => 'INV-004',
                                        ],
                                        null,
                                        ['class' => 'form-control select2', 'id' => 'invoice', 'placeholder' => 'Select Invoice'],
                                    ) }}
                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('product_service_name', 'Product/Service:') }}<span
                                        class="required">*</span>
                                    {{ Form::select(
                                        'product_service_name',
                                        [
                                            'Laptop Repair' => 'Laptop Repair',
                                            'Phone Screen Replacement' => 'Phone Screen Replacement',
                                            'Software License' => 'Software License',
                                            'Annual Maintenance' => 'Annual Maintenance',
                                        ],
                                        null,
                                        [
                                            'class' => 'form-control select2',
                                            'required',
                                            'id' => 'productService',
                                            'placeholder' => 'Select Product/Service',
                                        ],
                                    ) }}
                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('warranty_receipt_process', 'Receipt Process:') }}
                                    {{ Form::select(
                                        'warranty_receipt_process',
                                        [
                                            'email' => 'Email',
                                            'printed' => 'Printed',
                                            'digital' => 'Digital Wallet',
                                            'none' => 'None',
                                        ],
                                        null,
                                        ['class' => 'form-control select2', 'id' => 'receiptProcess', 'placeholder' => 'Select Process'],
                                    ) }}
                                </div>
                            </div>

                            <div class="form-group">
                                {{ Form::label('description', 'Description:') }}
                                {{ Form::textarea('description', null, ['class' => 'form-control summernote-simple', 'id' => 'warrantyDescription', 'rows' => 3]) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('client_note', 'Client Note:') }}
                                {{ Form::textarea('client_note', null, ['class' => 'form-control summernote-simple', 'id' => 'clientNote', 'rows' => 2]) }}
                            </div>

                            <div class="form-group mb-0">
                                {{ Form::label('admin_note', 'Admin Note:') }}
                                {{ Form::textarea('admin_note', null, ['class' => 'form-control summernote-simple', 'id' => 'adminNote', 'rows' => 2]) }}
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
    <script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.39.0/build/js/tempusdominus-bootstrap-4.min.js">
    </script>
@endsection

@section('scripts')
    <script>
        let warrantyCreateUrl = "{{ route('warranties.store') }}";

        $(document).ready(function() {
            // Generate claim code
            function generateClaimCode() {
                const timestamp = new Date().getTime().toString().slice(-5);
                const randomNum = Math.floor(Math.random() * 9000) + 1000;
                $('#claimCode').val(`#WCLAIM_${timestamp}${randomNum}`);
            }

            generateClaimCode();

            // Initialize Summernote for all textareas
            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });

            // Initialize DateTime Picker
            $('#warrantyDatePicker').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
                sideBySide: true,
                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar',
                    up: 'fas fa-chevron-up',
                    down: 'fas fa-chevron-down',
                    previous: 'fas fa-chevron-left',
                    next: 'fas fa-chevron-right'
                }
            });

            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            // Form submission
            $(document).on('submit', '#addWarrantyForm', function(e) {
                e.preventDefault();
                processingBtn('#addWarrantyForm', '#btnSave', 'loading');

                // Validate summernote fields
                const fieldsToValidate = ['warrantyDescription', 'clientNote', 'adminNote'];
                let isValid = true;

                fieldsToValidate.forEach(fieldId => {
                    let summernoteHtml = $(`#${fieldId}`).summernote('code');
                    let div = document.createElement('div');
                    div.innerHTML = summernoteHtml;
                    let plainText = div.textContent || div.innerText || "";

                    // Trim whitespace-only entries
                    plainText = plainText.trim();

                    if (!plainText) {
                        displayErrorMessage(
                            `${fieldId.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())} must not be empty.`
                        );
                        isValid = false;
                        return false;
                    }

                    // Set the stripped plain text back to the textarea
                    $(`#${fieldId}`).val(plainText);
                });

                $.ajax({
                    url: warrantyCreateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(result) {
                        if (result.success) {
                            displaySuccessMessage(result.message);
                            window.location.href = "{{ route('warranties.index') }}";
                        }
                    },
                    error: function(result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function() {
                        processingBtn('#addWarrantyForm', '#btnSave');
                    }
                });
            });
        });
    </script>
@endsection
