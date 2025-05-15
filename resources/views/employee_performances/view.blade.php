@extends('layouts.app')

@section('title')
    {{ __('messages.employee_performances.view') }}
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ __('messages.employee_performances.view') }}</h1>
        <div class="section-header-breadcrumb float-right"></div>
        <div class="float-right">
            <a href="{{ route('employee_performances.index') }}" class="btn btn-primary form-btn">
                {{ __('messages.employee_performances.list') }}
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('messages.employee_performances.name') }}:</strong>
                        <p>{{ $employeePerformance->employee->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('messages.employee_performances.review_period') }}:</strong>
                        <p>{{ $employeePerformance->review_period }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('messages.employee_performances.supervisor_info') }}:</strong>
                        <p>{{ $employeePerformance->supervisor_info }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('messages.employee_performances.total_score') }}:</strong>
                        <p>{{ $employeePerformance->total_score }}</p>
                    </div>
                </div>

                <hr>
                <h5>{{ __('messages.employee_performances.section_a') }}</h5>
                @php
                    $sectionA = is_string($employeePerformance->section_a) ? json_decode($employeePerformance->section_a, true) : [];
                @endphp
                @forelse ($sectionA as $index => $item)
                    <div class="mb-2 border p-2 rounded">
                        <p><strong>{{ ucwords(str_replace('_', ' ', $index)) }}:</strong> {{ $item }}</p>
                    </div>
                @empty
                    <p>{{ __('messages.common.no_data_available') }}</p>
                @endforelse

                <hr>
                <h5>{{ __('messages.employee_performances.section_b') }}</h5>
                @php
                    $sectionB = is_string($employeePerformance->section_b) ? json_decode($employeePerformance->section_b, true) : [];
                @endphp
                @forelse ($sectionB as $index => $item)
                    <div class="mb-2 border p-2 rounded">
                        <p><strong>{{ ucwords(str_replace('_', ' ', $index)) }}:</strong> {{ $item }}</p>
                    </div>
                @empty
                    <p>{{ __('messages.common.no_data_available') }}</p>
                @endforelse

                <hr>
                <h5>{{ __('messages.employee_performances.development_plan') }}</h5>
                @php
                    $developmentPlans = is_string($employeePerformance->development) ? json_decode($employeePerformance->development, true) : [];
                @endphp
                @forelse ($developmentPlans as $plan)
                    <div class="border p-2 mb-2 rounded">
                        <p><strong>Area:</strong> {{ $plan['area'] ?? '-' }}</p>
                        <p><strong>Expected Outcome:</strong> {{ $plan['expected'] ?? '-' }}</p>
                        <p><strong>Responsible Person:</strong> {{ $plan['responsible'] ?? '-' }}</p>
                        <p><strong>Start Date:</strong> {{ $plan['start_date'] ?? '-' }}</p>
                        <p><strong>End Date:</strong> {{ $plan['end_date'] ?? '-' }}</p>
                    </div>
                @empty
                    <p>{{ __('messages.common.no_data_available') }}</p>
                @endforelse

                <hr>
                <h5>{{ __('messages.employee_performances.goals') }}</h5>
                @php
                    $goals = is_string($employeePerformance->goals) ? json_decode($employeePerformance->goals, true) : [];
                @endphp
                @forelse ($goals as $goal)
                    <div class="border p-2 mb-2 rounded">
                        <p><strong>Goal:</strong> {{ $goal['goal'] ?? '-' }}</p>
                        <p><strong>Proposed Completion Date:</strong> {{ $goal['completion'] ?? '-' }}</p>
                    </div>
                @empty
                    <p>{{ __('messages.common.no_data_available') }}</p>
                @endforelse

                <hr>
                <h5>{{ __('messages.employee_performances.employee_comments') }}</h5>
                <p>{{ $employeePerformance->employee_comments ?? '-' }}</p>

                <hr>
                <h5>{{ __('messages.employee_performances.reviewer_information') }}</h5>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ __('messages.employee_performances.reviewer_name') }}:</strong> {{ $employeePerformance->reviewer_name ?? '-' }}</p>
                        <p><strong>{{ __('messages.employee_performances.reviewer_signature') }}:</strong> {{ $employeePerformance->reviewer_signature ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('messages.employee_performances.review_date') }}:</strong> {{ $employeePerformance->review_date ?? '-' }}</p>
                        <p><strong>{{ __('messages.employee_performances.next_review_period') }}:</strong> {{ $employeePerformance->next_review_period ?? '-' }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
