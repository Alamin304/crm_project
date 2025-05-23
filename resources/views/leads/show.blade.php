@extends('layouts.app')
@section('title')
    {{ __('messages.lead.lead_details') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" type="text/css">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.lead.lead_details') }}</h1>
            <div class="section-header-breadcrumb float-right">
                @if(!$lead->lead_convert_customer)
                    <a href="javascript:void(0)" class="btn btn-info mr-2 form-btn"
                       id="leadConvertToCustomer">{{ __('messages.lead.convert_to_customer') }}</a>
                @endif
            </div>
            <div class="float-right">
                <a href="{{ route('leads.edit', ['lead' => $lead->id]) }}"
                   class="btn btn-warning mr-2 form-btn">{{ __('messages.common.edit') }}
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-primary form-btn">{{ __('messages.lead.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                @include('flash::message')
                <div class="card-body">
                    @include('leads.show_fields')
                </div>
            </div>
            @include('leads.convert_to_customer')
        </div>
        @include('tasks.templates.templates')
    </section>
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
@section('scripts')
    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
    @include('livewire.livewire-turbo')
    <script>
        let statusArray = JSON.parse('@json($status)');
        let priorities = JSON.parse('@json($priorities)');
        let ownerId = "{{ $lead->id }}";
        let ownerType = 'App\\Models\\Lead';
        let leadUrl = "{{ route('leads.index') }}/";
        let leadId = '{{ $lead->id }}';
        let authId = '{{ getLoggedInUserId() }}';
        let ownerUrl = "{{ route('leads.index') }}";
        let customerId = true;
        let memberUrl = "{{ route('members.index') }}";
    </script>
    <script src="{{ mix('assets/js/leads/lead-convert-to-customer.js') }}"></script>
    <script src="{{ mix('assets/js/tasks/tasks.js')}}"></script>
    <script src="{{ mix('assets/js/notes/new-notes.js')}}"></script>
    <script src="{{ mix('assets/js/reminder/reminder.js')}}"></script>
    <script src="{{ mix('assets/js/custom/get-price-format.js') }}"></script>
    <script src="{{ mix('assets/js/proposals/proposals-datatable.js') }}"></script>
@endsection
