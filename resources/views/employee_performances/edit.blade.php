@extends('layouts.app')

@section('title')
    {{ __('messages.employee_performances.edit_performance') }}
@endsection

@section('page_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.employee_performances.edit_performance') }}</h1>
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
                    <div class="modal-content">
                        {{ Form::open(['id' => 'editPerformanceForm']) }}
                        {{ Form::hidden('_method', 'PUT') }}
                        {{ Form::hidden('id', $employeePerformance->id, ['id' => 'performance_id']) }}

                        <div class="modal-body">
                            <div class="alert alert-danger d-none" id="validationErrorsBox"></div>

                            <p class="text-danger">{{ __('messages.employee_performances.all_required_except_comments') }}
                            </p>

                            {{-- Employee Details --}}
                            <div class="form-group">
                                {{ Form::label('employee_id', __('messages.employee_performances.name') . ':') }}<span
                                    class="required">*</span>
                                <select name="employee_id" class="form-control" required>
                                    <option value="">{{ __('messages.employee_performances.select_employee') }}
                                    </option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ $employeePerformance->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                {{ Form::label('review_period', __('messages.employee_performances.review_period') . ':') }}<span
                                    class="required">*</span>
                                {{ Form::text('review_period', $employeePerformance->review_period, [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'editReviewPeriod',
                                ]) }}
                            </div>

                            <div class="form-group">
                                {{ Form::label('supervisor_info', __('messages.employee_performances.supervisor_info') . ':') }}<span
                                    class="required">*</span>
                                {{ Form::text('supervisor_info', $employeePerformance->supervisor_info, [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'editSupervisorInfo',
                                ]) }}
                            </div>

                            <div class="border p-2 mb-3">
                                <strong>{{ __('messages.employee_performances.rating_scale') }}</strong><br>
                                <small>{{ __('messages.employee_performances.rating_legend') }}:
                                    <b>P</b> (0), <b>NI</b> (3), <b>G</b> (6), <b>VG</b> (9), <b>E</b> (12)
                                </small>
                            </div>

                            {{-- Section A --}}
                            <h5 class="mt-4">{{ __('messages.employee_performances.section_a_title') }}</h5>
                            <div class="row font-weight-bold text-center">
                                <div class="col-md-4"></div>
                                @foreach (['P', 'NI', 'G', 'VG', 'E'] as $label)
                                    <div class="col">{{ $label }}</div>
                                @endforeach
                                <div class="col-md-1">Score</div>
                                <div class="col-md-3">Comments</div>
                            </div>

                            @php
                                $sectionA = [
                                    __('messages.employee_performances.knowledge_quality'),
                                    __('messages.employee_performances.timeliness'),
                                    __('messages.employee_performances.impact'),
                                    __('messages.employee_performances.overall_goal'),
                                    __('messages.employee_performances.beyond_duty'),
                                ];

                                // Check if it's an array or a string that needs to be decoded
                                $sectionAData = is_array($employeePerformance->section_a)
                                    ? $employeePerformance->section_a
                                    : json_decode($employeePerformance->section_a, true);
                            @endphp


                            @foreach ($sectionA as $index => $criteria)
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-4">{{ $criteria }}</div>
                                    @foreach ([0, 3, 6, 9, 12] as $score)
                                        <div class="col text-center">
                                            <input type="radio" name="section_a[{{ $index }}][score]"
                                                value="{{ $score }}" class="score-radio"
                                                {{ isset($sectionAData[$index]['score']) && $sectionAData[$index]['score'] == $score ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                    <div class="col-md-1">
                                        <input type="number" name="section_a[{{ $index }}][final_score]"
                                            class="form-control score-output" readonly
                                            value="{{ $sectionAData[$index]['final_score'] ?? 0 }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="section_a[{{ $index }}][comments]"
                                            class="form-control"
                                            placeholder="{{ __('messages.employee_performances.comments_placeholder') }}"
                                            value="{{ $sectionAData[$index]['comments'] ?? '' }}">
                                    </div>
                                </div>
                            @endforeach

                            {{-- Section B --}}
                            <h5 class="mt-5">{{ __('messages.employee_performances.section_b_title') }}</h5>
                            <div class="row font-weight-bold text-center">
                                <div class="col-md-4"></div>
                                @foreach (['P', 'NI', 'G', 'VG', 'E'] as $label)
                                    <div class="col">{{ $label }}</div>
                                @endforeach
                                <div class="col-md-1">Score</div>
                                <div class="col-md-3">Comments</div>
                            </div>

                            @php
                                $sectionB = [
                                    __('messages.employee_performances.teamwork'),
                                    __('messages.employee_performances.attendance'),
                                    __('messages.employee_performances.communication'),
                                    __('messages.employee_performances.contribution'),
                                ];

                                // Check if it's an array or a string that needs to be decoded
                                $sectionBData = is_array($employeePerformance->section_b)
                                    ? $employeePerformance->section_b
                                    : json_decode($employeePerformance->section_b, true);
                            @endphp


                            @foreach ($sectionB as $index => $criteria)
                                <div class="row mb-2 align-items-center">
                                    <div class="col-md-4">{{ $criteria }}</div>
                                    @foreach ([2, 4, 6, 9, 10] as $score)
                                        <div class="col text-center">
                                            <input type="radio" name="section_b[{{ $index }}][score]"
                                                value="{{ $score }}" class="score-radio"
                                                {{ isset($sectionBData[$index]['score']) && $sectionBData[$index]['score'] == $score ? 'checked' : '' }}>
                                        </div>
                                    @endforeach
                                    <div class="col-md-1">
                                        <input type="number" name="section_b[{{ $index }}][final_score]"
                                            class="form-control score-output" readonly
                                            value="{{ $sectionBData[$index]['final_score'] ?? 0 }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="section_b[{{ $index }}][comments]"
                                            class="form-control"
                                            placeholder="{{ __('messages.employee_performances.comments_placeholder') }}"
                                            value="{{ $sectionBData[$index]['comments'] ?? '' }}">
                                    </div>
                                </div>
                            @endforeach

                            {{-- Section C --}}
                            <h5 class="mt-5">{{ __('messages.employee_performances.section_c_title') }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    {{ Form::label('total_score', __('messages.employee_performances.total_score') . ':') }}
                                    {{ Form::number('total_score', $employeePerformance->total_score, [
                                        'class' => 'form-control',
                                        'id' => 'editTotalScore',
                                        'readonly',
                                    ]) }}
                                    <p class="text-muted mt-2">
                                        {{ __('messages.employee_performances.score_classification') }}</p>
                                </div>
                                <div class="col-md-6">
                                    {{ Form::label('reviewer_comments', __('messages.employee_performances.reviewer_comments') . ':') }}
                                    {{ Form::text('reviewer_name', $employeePerformance->reviewer_name, [
                                        'class' => 'form-control mb-2',
                                        'placeholder' => __('messages.employee_performances.reviewer_name'),
                                    ]) }}
                                    {{ Form::text('reviewer_signature', $employeePerformance->reviewer_signature, [
                                        'class' => 'form-control mb-2',
                                        'placeholder' => __('messages.employee_performances.reviewer_signature'),
                                    ]) }}
                                    {{ Form::date('review_date', $employeePerformance->review_date, [
                                        'class' => 'form-control mb-2',
                                    ]) }}
                                    {{ Form::text('next_review_period', $employeePerformance->next_review_period, [
                                        'class' => 'form-control',
                                        'placeholder' => __('messages.employee_performances.next_review_period'),
                                    ]) }}
                                </div>
                            </div>

                            {{-- Section D --}}
                            <h5 class="mt-5">{{ __('messages.employee_performances.section_d_title') }}</h5>
                            {{ Form::textarea('employee_comments', $employeePerformance->employee_comments, [
                                'class' => 'form-control',
                                'rows' => 4,
                                'maxlength' => 500,
                                'id' => 'editEmployeeComments',
                            ]) }}

                            {{-- Section E: Development Plan
                            <h5 class="mt-5">{{ __('messages.employee_performances.section_e_title') }}</h5>
                            <div id="development-plan-container">
                                @php
                                    $developmentPlans =
                                        json_decode($employeePerformance->development_plans, true) ?? [];
                                @endphp

                                @foreach ($developmentPlans as $index => $plan)
                                    <div class="row mb-3 development-item">
                                        <div class="col">
                                            <input type="text" name="development[{{ $index }}][area]"
                                                class="form-control" placeholder="Area for improvement"
                                                value="{{ $plan['area'] ?? '' }}">
                                        </div>
                                        <div class="col">
                                            <input type="text" name="development[{{ $index }}][expected]"
                                                class="form-control" placeholder="Expected outcomes"
                                                value="{{ $plan['expected'] ?? '' }}">
                                        </div>
                                        <div class="col">
                                            <input type="text" name="development[{{ $index }}][action]"
                                                class="form-control" placeholder="Action"
                                                value="{{ $plan['action'] ?? '' }}">
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-danger remove-development">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-info" id="addDevelopmentPlan">
                                <i class="fa fa-plus"></i> {{ __('messages.employee_performances.add_plan') }}
                            </button>
                        </div> --}}
                            <div class="modal-footer">
                                <button type="submit"
                                    class="btn btn-primary">{{ __('messages.employee_performances.update') }}</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection



@section('page_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection

@section('scripts')
    <script>
        let updateUrl = "{{ route('employee_performances.update', $employeePerformance->id) }}";
        let devIndex =
            {{ is_array(json_decode($employeePerformance->development_plans, true)) ? count(json_decode($employeePerformance->development_plans, true)) : 1 }};
        let goalIndex =
            {{ is_array($employeePerformance->goals) ? count($employeePerformance->goals) : (is_string($employeePerformance->goals) ? count(json_decode($employeePerformance->goals, true)) : 1) }};


        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2();

            // Score auto-fill and total score calculation
            $('body').on('change', '.score-radio', function() {
                const row = $(this).closest('.row');
                const score = parseInt($(this).val());
                row.find('.score-output').val(score);

                calculateTotalScore();
            });

            // Calculate total score
            function calculateTotalScore() {
                let total = 0;
                $('.score-output').each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                $('#editTotalScore').val(total);
            }

            // Add/Remove Development Plan
            $('#add-development').click(function() {
                const newDev = `
                    <div class="row mb-3 development-item">
                        <div class="col"><input type="text" name="development[${devIndex}][area]" class="form-control" placeholder="Area for improvement"></div>
                        <div class="col"><input type="text" name="development[${devIndex}][expected]" class="form-control" placeholder="Expected outcomes"></div>
                        <div class="col"><input type="text" name="development[${devIndex}][responsible]" class="form-control" placeholder="Responsible person"></div>
                        <div class="col"><input type="date" name="development[${devIndex}][start_date]" class="form-control"></div>
                        <div class="col"><input type="date" name="development[${devIndex}][end_date]" class="form-control"></div>
                        <div class="col-auto d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-development"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>`;
                $('#development-plan-container').append(newDev);
                devIndex++;
            });

            $('body').on('click', '.remove-development', function() {
                $(this).closest('.development-item').remove();
            });

            // Add/Remove Goals
            $('#add-goal').click(function() {
                const newGoal = `
                    <div class="row mb-3 goal-item">
                        <div class="col"><input type="text" name="goals[${goalIndex}][goal]" class="form-control" placeholder="Goal"></div>
                        <div class="col"><input type="date" name="goals[${goalIndex}][completion]" class="form-control"></div>
                        <div class="col-auto d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-goal"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>`;
                $('#goals-container').append(newGoal);
                goalIndex++;
            });

            $('body').on('click', '.remove-goal', function() {
                $(this).closest('.goal-item').remove();
            });

            // Form submission
            $('#editPerformanceForm').on('submit', function(event) {
                event.preventDefault();
                let btnSave = $('#btnSave');
                btnSave.prop('disabled', true);
                btnSave.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                $.ajax({
                    url: updateUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            displaySuccessMessage(response.message);
                            window.location.href =
                                "{{ route('employee_performances.index') }}";
                        }
                    },

                    error: function(xhr) {
                        btnSave.prop('disabled', false);
                        btnSave.html('{{ __('messages.common.submit') }}');

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            displayErrors(xhr.responseJSON.errors);
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            displayErrorMessage(xhr.responseJSON.message);
                        } else {
                            displayErrorMessage('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            function displayErrors(errors) {
                let html = '<ul>';
                $.each(errors, function(key, value) {
                    html += '<li>' + value[0] + '</li>';
                });
                html += '</ul>';

                $('#validationErrorsBox').html(html);
                $('#validationErrorsBox').removeClass('d-none');
            }

            function displaySuccessMessage(message) {
                toastr.success(message);
            }

            function displayErrorMessage(message) {
                toastr.error(message);
            }
        });
    </script>
@endsection
