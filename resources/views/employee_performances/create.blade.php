@extends('layouts.app')

@section('title')
    {{ __('messages.employee_performances.employee_performances') }}
@endsection

@section('content')
    <div class="container">
        <form action="{{ route('employee_performances.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ __('messages.employee_performances.new_performance') }}</h4>
                    <a href="{{ route('employee_performances.index') }}"
                        class="btn btn-primary">{{ __('messages.employee_performances.list') }}</a>
                </div>
                <div class="card-body">
                    <p class="text-danger">{{ __('messages.employee_performances.all_required_except_comments') }}</p>

                    {{-- Employee Details --}}
                    <div class="form-group">
                        <label>{{ __('messages.employee_performances.name') }}:</label>
                        <select name="employee_id" class="form-control" required>
                            <option value="">{{ __('messages.employee_performances.select_employee') }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ __('messages.employee_performances.review_period') }}:</label>
                        <input type="text" name="review_period" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>{{ __('messages.employee_performances.supervisor_info') }}:</label>
                        <input type="text" name="supervisor_info" class="form-control" required>
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
                    @endphp
                    @foreach ($sectionA as $index => $criteria)
                        <div class="row mb-2 align-items-center">
                            <div class="col-md-4">{{ $criteria }}</div>
                            @foreach ([0, 3, 6, 9, 12] as $score)
                                <div class="col text-center">
                                    <input type="radio" name="section_a[{{ $index }}][score]"
                                        value="{{ $score }}" class="score-radio">
                                </div>
                            @endforeach
                            <div class="col-md-1">
                                <input type="number" name="section_a[{{ $index }}][final_score]"
                                    class="form-control score-output" readonly value="0">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="section_a[{{ $index }}][comments]" class="form-control"
                                    placeholder="{{ __('messages.employee_performances.comments_placeholder') }}">
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
                    @endphp
                    @foreach ($sectionB as $index => $criteria)
                        <div class="row mb-2 align-items-center">
                            <div class="col-md-4">{{ $criteria }}</div>
                            @foreach ([2, 4, 6, 9, 10] as $score)
                                <div class="col text-center">
                                    <input type="radio" name="section_b[{{ $index }}][score]"
                                        value="{{ $score }}" class="score-radio">
                                </div>
                            @endforeach
                            <div class="col-md-1">
                                <input type="number" name="section_b[{{ $index }}][final_score]"
                                    class="form-control score-output" readonly value="0">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="section_b[{{ $index }}][comments]" class="form-control"
                                    placeholder="{{ __('messages.employee_performances.comments_placeholder') }}">
                            </div>
                        </div>
                    @endforeach

                    {{-- Section C --}}
                    <h5 class="mt-5">{{ __('messages.employee_performances.section_c_title') }}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label>{{ __('messages.employee_performances.total_score') }}:</label>
                            <input type="number" name="total_score" id="total_score" class="form-control" readonly
                                value="0">
                            <p class="text-muted mt-2">{{ __('messages.employee_performances.score_classification') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label>{{ __('messages.employee_performances.reviewer_comments') }}:</label>
                            <input type="text" name="reviewer_name" class="form-control mb-2"
                                placeholder="{{ __('messages.employee_performances.reviewer_name') }}">
                            <input type="text" name="reviewer_signature" class="form-control mb-2"
                                placeholder="{{ __('messages.employee_performances.reviewer_signature') }}">
                            <input type="date" name="review_date" class="form-control mb-2">
                            <input type="text" name="next_review_period" class="form-control"
                                placeholder="{{ __('messages.employee_performances.next_review_period') }}">
                        </div>
                    </div>

                    {{-- Section D --}}
                    <h5 class="mt-5">{{ __('messages.employee_performances.section_d_title') }}</h5>
                    <textarea name="employee_comments" rows="4" class="form-control" maxlength="500"></textarea>

                    <!-- Section E: Development Plan -->
                    <h5 class="mt-5">{{ __('messages.employee_performances.section_e_title') }}</h5>
                    <div id="development-plan-container">
                        <div class="row mb-3 development-item">
                            <div class="col"><input type="text" name="development[0][area]" class="form-control"
                                    placeholder="Area for improvement"></div>
                            <div class="col"><input type="text" name="development[0][expected]"
                                    class="form-control" placeholder="Expected outcomes"></div>
                            <div class="col"><input type="text" name="development[0][responsible]"
                                    class="form-control" placeholder="Responsible person"></div>
                            <div class="col"><input type="date" name="development[0][start_date]"
                                    class="form-control"></div>
                            <div class="col"><input type="date" name="development[0][end_date]"
                                    class="form-control"></div>
                            <div class="col-auto d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm remove-development"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2" id="add-development"><i
                            class="fa fa-plus"></i> Add Development</button>

                    <!-- Section F: Goals -->
                    <h5 class="mt-5">{{ __('messages.employee_performances.section_f_title') }}</h5>
                    <div id="goals-container">
                        <div class="row mb-3 goal-item">
                            <div class="col"><input type="text" name="goals[0][goal]" class="form-control"
                                    placeholder="Goal"></div>
                            <div class="col"><input type="date" name="goals[0][completion]" class="form-control">
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm remove-goal"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2" id="add-goal"><i class="fa fa-plus"></i>
                        Add Goal</button>


                    <div class="text-right mt-3">
                        <button type="submit" class="btn btn-primary" id="btnSave">
                            {{ __('messages.common.submit') }}
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let devIndex = 1;
            let goalIndex = 1;

            // Score auto-fill and total score calculation
            document.querySelectorAll('.score-radio').forEach(radio => {
                radio.addEventListener('change', () => {
                    const row = radio.closest('.row');
                    const score = parseInt(radio.value);
                    row.querySelector('.score-output').value = score;

                    let total = 0;
                    document.querySelectorAll('.score-output').forEach(input => {
                        total += parseInt(input.value) || 0;
                    });
                    document.getElementById('total_score').value = total;
                });
            });

            // Add/Remove Development Plan
            const developmentContainer = document.getElementById('development-plan-container');
            document.getElementById('add-development').addEventListener('click', function () {
                const newDev = document.createElement('div');
                newDev.classList.add('row', 'mb-3', 'development-item');
                newDev.innerHTML = `
                    <div class="col"><input type="text" name="development[${devIndex}][area]" class="form-control" placeholder="Area for improvement"></div>
                    <div class="col"><input type="text" name="development[${devIndex}][expected]" class="form-control" placeholder="Expected outcomes"></div>
                    <div class="col"><input type="text" name="development[${devIndex}][responsible]" class="form-control" placeholder="Responsible person"></div>
                    <div class="col"><input type="date" name="development[${devIndex}][start_date]" class="form-control"></div>
                    <div class="col"><input type="date" name="development[${devIndex}][end_date]" class="form-control"></div>
                    <div class="col-auto d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-development"><i class="fa fa-trash"></i></button>
                    </div>
                `;
                developmentContainer.appendChild(newDev);
                devIndex++;
            });

            developmentContainer.addEventListener('click', function (e) {
                if (e.target.closest('.remove-development')) {
                    e.target.closest('.development-item').remove();
                }
            });

            // Add/Remove Goals
            const goalsContainer = document.getElementById('goals-container');
            document.getElementById('add-goal').addEventListener('click', function () {
                const newGoal = document.createElement('div');
                newGoal.classList.add('row', 'mb-3', 'goal-item');
                newGoal.innerHTML = `
                    <div class="col"><input type="text" name="goals[${goalIndex}][goal]" class="form-control" placeholder="Goal"></div>
                    <div class="col"><input type="date" name="goals[${goalIndex}][completion]" class="form-control"></div>
                    <div class="col-auto d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-goal"><i class="fa fa-trash"></i></button>
                    </div>
                `;
                goalsContainer.appendChild(newGoal);
                goalIndex++;
            });

            goalsContainer.addEventListener('click', function (e) {
                if (e.target.closest('.remove-goal')) {
                    e.target.closest('.goal-item').remove();
                }
            });

            // AJAX form submission
            $('#addNewPerformanceForm').on('submit', function (e) {
                e.preventDefault();
                var btnSave = $('#btnSave');
                btnSave.prop('disabled', true);
                btnSave.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            displaySuccessMessage(response.message);
                            setTimeout(function () {
                                window.location.href = "{{ route('employee_performances.index') }}";
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        btnSave.prop('disabled', false);
                        btnSave.html('{{ __('messages.common.submit') }}');

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            displayErrorMessage(xhr.responseJSON.message);
                        } else {
                            displayErrorMessage('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            function displaySuccessMessage(message) {
                toastr.success(message);
            }

            function displayErrorMessage(message) {
                toastr.error(message);
            }
        });
    </script>
@endsection
