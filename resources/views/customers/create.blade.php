@extends('layouts.app')
@section('title')
    {{ __('messages.customer.new_customer') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/int-tel/css/intlTelInput.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #statusSwitch.form-check-input {
            width: 3em;
            height: 1.5em;
        }

        #statusSwitch.form-check-input:checked {
            background-color: #0d6efd;
            /* Change the color as needed */
        }

        #statusSwitch.form-check-input::before {
            width: 1.5em;
            height: 1.5em;
        }

        #drop-area {

            height: 200px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: 20px auto;
            color: #888;
            cursor: pointer;
        }

        #drop-area.hover {
            border-color: #0d6efd;
        }

        #preview {
            margin-top: 20px;
            max-width: 100%;
            height: auto;
        }

        #file-input {
            display: none;
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.customer.new_customer') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('customers.index') }}"
                    class="btn btn-primary form-btn float-right-mobile">{{ __('messages.common.back') }}</a>
            </div>
        </div>
        <div class="section-body">
            @include('layouts.errors')
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => 'customers.store', 'id' => 'createCustomer', 'files' => true]) }}

                    @include('customers.new_fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
    @include('customers.customer_group_modal')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/int-tel/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('assets/js/int-tel/js/utils.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        let utilsScript = "{{ asset('assets/js/int-tel/js/utils.min.js') }}"
        let phoneNo = "{{ old('prefix_code') . old('phone') }}"
        let isEdit = false
        let localizeMessage = "{{ __('messages.placeholder.select_groups') }}"
    </script>
    <script src="{{ mix('assets/js/custom/phone-number-country-code.js') }}"></script>
    <script src="{{ mix('assets/js/customers/create-edit.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <script>
        $(document).ready(function() {
            $('#billingState').select2({
                width: '100%', // Set the width of the select element
                allowClear: true // Allow clearing the selection
            });
            $('#paymentModes').select2({
                placeholder: Lang.get('messages.placeholder.select_payment_mode'),
                multiple: true,
            })
        });
    </script>
    <script>
        $(document).ready(function() {
            // Encode the states data into a JavaScript object
            var statesData = @json($states->groupBy('country_id'));

            // Function to update states based on the selected country
            function updateStates() {
                var selectedCountryId = $('#billingCountryId').val();
                var $stateSelect = $('#billingState');
                $stateSelect.empty(); // Clear existing options

                // Check if there are states for the selected country
                if (statesData[selectedCountryId]) {
                    $.each(statesData[selectedCountryId], function(index, state) {
                        $stateSelect.append($('<option>', {
                            value: state.id,
                            text: state.name
                        }));
                    });
                }
            }

            // Event listener for country change
            $('#billingCountryId').change(updateStates);

            // Initial state update
            updateStates();
        });
    </script>
    <script>
        $(document).ready(function() {
            var dropArea = $('#drop-area');
            var fileInput = $('#file-input');
            var preview = $('#preview');

            // Highlight drop area when file is dragged over it
            dropArea.on('dragenter dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.addClass('hover');
            });

            // Remove highlight when dragging out of the drop area
            dropArea.on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.removeClass('hover');
            });

            // Handle file drop
            dropArea.on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropArea.removeClass('hover');

                var files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            // Trigger file input on click for fallback
            dropArea.on('click', function() {
                fileInput.click();
            });

            // Handle file selection via input
            fileInput.on('change', function() {
                var files = this.files;
                handleFiles(files);
            });

            // Function to handle file and preview it
            function handleFiles(files) {
                if (files.length > 0) {
                    var file = files[0];

                    if (file.type.startsWith('image/')) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            preview.attr('src', e.target.result);
                        };

                        reader.readAsDataURL(file);
                    } else {
                        alert('Please upload a valid image file.');
                    }
                }
            }
        });
    </script>
@endsection
