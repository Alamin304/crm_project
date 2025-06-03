@extends('layouts.app')
@section('title')
    {{ __('messages.second_assets.edit') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.second_assets.edit') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('second-assets.index') }}" class="btn btn-primary form-btn float-right">
                    {{ __('messages.second_assets.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    {{ Form::model($secondAsset, ['route' => ['second-assets.update', $secondAsset->id], 'method' => 'put', 'id' => 'editSecondAssetForm']) }}
                    @include('second_assets.fields')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: true,
                sideBySide: true,
                icons: {
                    up: "fas fa-chevron-up",
                    down: "fas fa-chevron-down",
                    next: 'fas fa-chevron-right',
                    previous: 'fas fa-chevron-left'
                }
            });

            $('#forSellCheckbox').change(function() {
                if($(this).is(':checked')) {
                    $('.sell-price-field').show();
                    $('input[name="selling_price"]').attr('required', true);
                } else {
                    $('.sell-price-field').hide();
                    $('input[name="selling_price"]').attr('required', false).val('');
                }
            });

            $('#forRentCheckbox').change(function() {
                if($(this).is(':checked')) {
                    $('.rent-fields').show();
                    $('input[name="rental_price"]').attr('required', true);
                    $('input[name="minimum_renting_price"]').attr('required', true);
                    $('select[name="unit"]').attr('required', true);
                } else {
                    $('.rent-fields').hide();
                    $('input[name="rental_price"]').attr('required', false).val('');
                    $('input[name="minimum_renting_price"]').attr('required', false).val('');
                    $('select[name="unit"]').attr('required', false).val('');
                }
            });

            // Trigger change event if values exist
            @if($secondAsset->for_sell)
                $('#forSellCheckbox').trigger('change');
            @endif

            @if($secondAsset->for_rent)
                $('#forRentCheckbox').trigger('change');
            @endif

            $('.summernote-simple').summernote({
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
@endsection
