@extends('layouts.app')
@section('title')
    {{ __('messages.goals') }}
@endsection
@section('css')
    @livewireStyles
@endsection
@section('page_css')
    <link href="{{ mix('assets/css/goals/goals.css') }}" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.goals') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-2 select2-mobile-margin">
                    {{ Form::select('goal_type',$goalTypes,null,['id' => 'goalTypeId','class' => 'form-control','placeholder' => __('messages.placeholder.select_goal_type')]) }}
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('goals.create') }}"
                   class="btn btn-primary form-btn">{{ __('messages.goal.add') }} </a>
            </div>
        </div>
        @include('flash::message')
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @livewire('goals')
                </div>
            </div>
        </div>
    </section>
@endsection
@section('page_scripts')
@endsection
@section('scripts')
    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
    @include('livewire.livewire-turbo')
    <script>
        let statusArray = JSON.parse('@json($goalTypes)');
    </script>
    <script src="{{mix('assets/js/goals/goals.js')}}"></script>
@endsection
