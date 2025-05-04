@extends('layouts.app')
@section('title')
    {{ __('messages.leave-applications.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.leave-applications.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="row">
                @if ($application->status == 0)
                    <div class="col pr-1">
                        <button class="btn btn-success action-btn has-icon approve-btn "
                            style="width: 150px;height:40px;font-size:15px;" data-id="{{ $application->id }}">
                            <i class="fa fa-check"></i>Approve
                        </button>
                    </div>
                @endif


                <div class="col pl-0">
                    <a href="{{ route('approval-leaves.index') }}" class="btn btn-primary"
                        style="line-height: 30px;">{{ __('messages.leave-applications.list') }}</i>
                    </a>
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>
                                        {{ Form::label('end_date', __('messages.branches.name')) }}</strong>
                                    <p style="color: #555;">{{ $application->branch?->name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <div class="form-group">
                                    <strong>{{ Form::label('employee_id', __('messages.employees.id')) }}</strong>
                                    <p style="color: #555;">{{ $application->employee->iqama_no ?? '' }}</p>
                                </div>

                            </div>
                            <div class="col-lg-4 col-6">
                                <div class="form-group">
                                    <strong>{{ Form::label('employee_id', __('messages.leave-applications.employee')) }}</strong>
                                    <p style="color: #555;">{{ $application->employee->name }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <div class="form-group">
                                    <strong>{{ Form::label('employee_id', __('messages.designations.name')) }}</strong>
                                    <p style="color: #555;">{{ $application->employee->designation->name ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>{{ Form::label('from_date', __('messages.leave-applications.from_date')) }}</strong>
                                    <p style="color: #555;">{{ $application->from_date }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>
                                        {{ Form::label('end_date', __('messages.leave-applications.end_date')) }}</strong>
                                    <p style="color: #555;">{{ $application->end_date }}</p>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>
                                        {{ Form::label('total_days', __('messages.leave-applications.total_days')) }}</strong>
                                    <p style="color: #555;">{{ $application->total_days }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>
                                        {{ Form::label('leave_id', __('messages.leave-applications.leave_type')) }}</strong>
                                    <p style="color: #555;">{{ $application->leave->name }}</p>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>{{ Form::label('hard_copy', __('messages.leave-applications.hard_copy')) }}</strong>
                                    <p>
                                        <a target="_blank"
                                            href="/uploads/public/leave_applications/{{ rawurlencode($application->hard_copy) }}">
                                            {{ $application->hard_copy }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>
                                        {{ Form::label('leave_id', 'Status') }}</strong>
                                    <p
                                        style="color: {{ $application->status ? '#28a745' : '#dc3545' }}; font-weight: bold;">
                                        {{ $application->status ? 'Approved' : 'Pending' }}
                                    </p>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <strong>
                                        {{ Form::label('leave_id', 'Approved By') }}</strong>
                                    <p style="color: #555;">{{ $application->approvedBy?->fullName ?? '' }}</p>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <strong>
                                        {{ Form::label('title', __('messages.leave-applications.reason')) }}</strong>
                                    {!! $application->description !!}
                                </div>
                            </div>

                        </div>
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
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script>
        $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            swal({
                    title: 'Are you sure!!',
                    text: "Do you want to approve this leave application?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6', // Optional: Change confirm button color
                    cancelButtonColor: '#d33', // Optional: Change cancel button color
                },
                function() {
                    approveLeave(id);
                });

        });

        // Function to handle approval action
        function approveLeave(id) {
            startLoader();
            $.ajax({
                url: `{{ route('approval-leaves.update', ':id') }}`.replace(':id', id),
                method: 'get',
                success: function(response) {
                    displaySuccessMessage("leave Application Approved");
                    window.location.href = "{{ route('approval-leaves.index') }}";

                },
                error: function(response) {
                    displayErrorMessage("Failed to Update");
                    window.location.href = "{{ route('approval-leaves.index') }}";

                }
            });
        }
    </script>
@endsection
