@extends('layouts.app')
@section('title')
    {{ __('messages.work_centers.view') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.work_centers.view') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <a href="{{ route('work-centers.index') }}"
                   class="btn btn-primary form-btn">
                    {{ __('messages.work_centers.list') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{ __('messages.work_centers.name') }}</th>
                                        <td>{{ $workCenter->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.code') }}</th>
                                        <td>{{ $workCenter->code }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.working_hours') }}</th>
                                        <td>{{ $workCenter->working_hours }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.time_efficiency') }}</th>
                                        <td>{{ $workCenter->time_efficiency }}%</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.cost_per_hour') }}</th>
                                        <td>${{ number_format($workCenter->cost_per_hour, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.capacity') }}</th>
                                        <td>{{ $workCenter->capacity }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.oee_target') }}</th>
                                        <td>{{ $workCenter->oee_target }}%</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.time_before_prod') }}</th>
                                        <td>{{ $workCenter->time_before_prod }} minutes</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.time_after_prod') }}</th>
                                        <td>{{ $workCenter->time_after_prod }} minutes</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('messages.work_centers.description') }}</th>
                                        <td>{!! $workCenter->description !!}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
