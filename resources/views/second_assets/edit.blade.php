@extends('layouts.app')
@section('title')
    {{ __('messages.second_assets.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link href="{{ asset('assets/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.assets.edit') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('second-assets.index') }}" class="btn btn-primary form-btn float-right">
                    {{ __('messages.second_assets.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($asset, ['route' => ['second-assets.update', $asset->id], 'method' => 'put', 'id' => 'editAssetForm']) }}
                    <div class="row">
                        @include('second_assets.fields')
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        let assetUpdateUrl = "{{ route('second-assets.update', ['asset' => $asset->id]) }}";
        let assetIndexUrl = "{{ route('second-assets.index') }}";

        $(document).ready(function () {
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

            // Initialize Datepicker
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true
            });

            // Initialize Select2
            $('.select2').select2({
                width: '100%'
            });

            // Toggle selling price field
            $('#forSale').change(function () {
                if ($(this).is(':checked')) {
                    $('.for-sale-field').show();
                    $('#selling_price').attr('required', true);
                } else {
                    $('.for-sale-field').hide();
                    $('#selling_price').attr('required', false).val('');
                }
            });

            // Toggle rental fields
            $('#forRent').change(function () {
                if ($(this).is(':checked')) {
                    $('.for-rent-field').show();
                    $('#rental_price').attr('required', true);
                    $('#minimum_renting_days').attr('required', true);
                    $('#rental_unit').attr('required', true);
                } else {
                    $('.for-rent-field').hide();
                    $('#rental_price').attr('required', false).val('');
                    $('#minimum_renting_days').attr('required', false).val('');
                    $('#rental_unit').attr('required', false).val(null).trigger('change');
                }
            });

            // Trigger initial toggle state
            $('#forSale').trigger('change');
            $('#forRent').trigger('change');

            // Generate serial number
            $('#generateSerialBtn').click(function () {
                $.ajax({
                    url: "{{ route('second-assets.generate-serial') }}",
                    type: 'GET',
                    success: function (response) {
                        $('#serialNumber').val(response.data);
                    },
                    error: function (xhr) {
                        console.error(xhr);
                    }
                });
            });

            // Handle form submission via AJAX
            $('#editAssetForm').submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let loadingText = '<span class="spinner-border spinner-border-sm"></span> Processing...';
                let submitBtn = form.find('button[type="submit"]');
                let originalText = submitBtn.html();

                submitBtn.html(loadingText).prop('disabled', true);

                $.ajax({
                    url: url,
                    type: method,
                    data: form.serialize(),
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            window.location.href = assetIndexUrl;
                        }
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.message || 'An error occurred');
                        submitBtn.html(originalText).prop('disabled', false);

                        if (xhr.responseJSON.errors) {
                            let errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                toastr.error(errors[field][0]);
                            }
                        }
                    }
                });
            });
        });
    </script>
    <script src="{{ mix('assets/js/second_assets/create-edit.js') }}"></script>
@endsection

