@extends('layouts.app')
@section('title')
    {{ __('messages.contact.estimates') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/owl.carousel.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ mix('assets/css/clients/estimates/estimates.css') }}">
@endsection
@section('css')
    @livewireStyles
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.contact.estimates') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                    {{Form::select('status', $statusArr, null, ['id' => 'filterStatus', 'class' => 'form-control','placeholder' =>__('messages.placeholder.select_status')]) }}
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('estimates.create') }}"
                   class="btn btn-primary form-btn">{{ __('messages.common.add') }}
                    </a>
            </div>
        </div>
        <div class="section-body">
            @include('flash::message')
            <div class="card">
                <div class="card-body">
                    @livewire('estimates')
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
    @include('livewire.livewire-turbo')
    <script>
        let customerId = null;
    </script>
    <script src="{{ mix('assets/js/estimates/estimates-datatable.js') }}"></script>
    <script src="{{mix('assets/js/status-counts/status-counts.js')}}"></script>
@endsection

