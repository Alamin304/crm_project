@php
    use Carbon\Carbon;

    $com_logo = '/company_logo_color.png';

    $format = $settings['print_format'] ?? 1;
    if ($format == 2) {
        $com_logo = '/company_logo_color_ran.png';
    }

    $baseImagePath = public_path('img/company');
    // Company logo
    $imagePath = $baseImagePath . $com_logo;

    $imageData = file_get_contents($imagePath);
    $base64 = base64_encode($imageData);
    $company_logo = 'data:image/png;base64,' . $base64; // Ensure correct format

    $bgColor = '#fff7f2';
    $bColor = '#e2e2e2';

    // Parse the salary month and get the total days in the month
    $salaryMonth = Carbon::parse($salarySheet->salaryGenerate->salary_month);
    $totalDaysInMonth = $salaryMonth->daysInMonth;

    // Define daily working hours
    $dailyWorkingHours = 8;

    // Calculate total working hours for the month
    $totalWorkingHoursInMonth = $totalDaysInMonth * $dailyWorkingHours;

    // Calculate Worked Days
    $workedAndOvertimeHours = $salarySheet->worked_hours + $salarySheet->overtime_hours;
    $workedDays = $workedAndOvertimeHours / $dailyWorkingHours;

    // Calculate LOP (Loss of Pay) Days
    $lopHours = $salarySheet->absence_hours; // Total absence hours
    $lopDays = $lopHours / $dailyWorkingHours;
@endphp


@extends('layouts.app')
@section('title')
    {{ __('messages.salary_generates.salary_generates') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }

        .content_header {
            display: table;
            width: 100%;
            background-color: {{ $bgColor }};
            /* Set the background color */
            border: 1px solid {{ $bColor }};
            /* Optional border */
            padding: 10px;
            margin-bottom: 20px;
        }

        .content_header .left,
        .content_header .middle,
        .content_header .right {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .left {
            display: block;
            width: 2.5cm;
            height: 2.62cm;
        }

        .left div {
            width: 2.5cm;
            height: 2.62cm;
            background-image: url('{{ $company_logo }}');
            background-size: 100%;
            /* Ensures the image covers the entire container */
            background-position: center;
            /* Centers the image */
            background-repeat: no-repeat;
            /* Prevents tiling */
        }

        .content_header .left {
            text-align: left;
            width: 20%;
        }

        .content_header .middle {
            text-align: center;
            width: 60%;
        }

        .content_header .right {
            text-align: right;
            width: 20%;
        }

        .content_header img {
            max-width: 100%;
            max-height: 50px;
        }

        .content_header h2 {
            margin: 0;
            font-size: 18px;
        }

        .content_header .net_pay {
            font-weight: bold;
            font-size: 16px;
        }

        .content_body {

            margin-left: .8cm;
            margin-right: .8cm;
        }

        .employee_info {
            border-collapse: collapse;
            width: 100%;
            /* Adjust the width as needed */
        }

        .employee_info th,
        .employee_info td {
            padding: 1px;
            text-align: left;
        }

        .employee_info th {}

        .employee_info .header-row {}

        .bColor {
            border: .05cm solid #e2e2e2 !important;
        }

        .bgColor {
            background: #fff7f2 !important;
        }

        .salary_info {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid {{ $bColor }};
            /* Add overall table border */
            font-family: Arial, sans-serif;
            /* Set font for better readability */
            font-size: 14px;
            margin-top: 0.60cm;
            /* Adjust font size as needed */
        }

        .salary_info th,
        .salary_info td {
            border: 1px solid {{ $bColor }};
            padding: 8px;
            text-align: left;

        }

        .salary_info th {
            background: {{ $bgColor }};
            text-align: center;
        }

        .salary_info .total-row {
            font-weight: bold;
            /* Bold font for total row */
        }

        .salary_info .amount-in-words {
            font-style: italic;
            /* Italic font for amount in words */
        }

        .text-right {
            text-align: right !important;
        }

        .textLeftPadding {
            padding-left: 30px !important;
        }
    </style>
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.employee_salaries.name') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('employee-salaries.index') }}" class="btn btn-primary form-btn">
                    {{ __('messages.salary_generates.list') }}</a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        @if (session()->has('flash_notification'))
                            @foreach (session('flash_notification') as $message)
                                <div class="alert alert-{{ $message['level'] }}">
                                    {{ $message['message'] }}
                                </div>
                            @endforeach
                        @endif
                        <div class="contents" style="border:1px solid #e2e2e2;border-radius:5px;">
                            <div class="content_header">
                                <div class="left">
                                    <div>
                                    </div>
                                </div>
                                <div class="middle">
                                    <h1 style="font-size: 20pt;">Payslip</h1>
                                    <p></p>
                                    <p>{{ html_entity_decode($settings['company'] ?? '') }}<br>{{ $salarySheet->branch?->name ?? '' }}
                                    </p>
                                </div>
                                <div class="right">
                                    <h1 style="font-size: 20pt;">NET
                                        PAY<br>{{ number_format($salarySheet->net_salary ?? 0, 2) }}</h1>
                                </div>
                            </div>
                            <div class="content_body">
                                <table class="employee_info">
                                    <tr>
                                        <th colspan="2" class="header-row">Employee ID</th>
                                        <th colspan="2" class="header-row textLeftPadding">Employee Name</th>
                                        <th colspan="2" class="header-row textLeftPadding">Date of Joining</th>
                                        <th colspan="2" class="header-row textLeftPadding">Designation</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ $salarySheet->employee?->iqama_no ?? 'N/A' }}</td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ $salarySheet->employee?->name ?? 'N/A' }}</td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ $salarySheet->employee?->join_date ? $salarySheet->employee?->join_date->format('d-m-Y') : 'N/A' }}
                                        </td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ $salarySheet->employee?->designation?->name ?? 'N/A' }}</td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <th colspan="2" class="header-row">Department</th>
                                        <th colspan="2" class="header-row textLeftPadding">Bank Account</th>
                                        <th colspan="2" class="header-row textLeftPadding">Bank Name</th>
                                        <th colspan="2" class="header-row textLeftPadding">Pay Period</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ $salarySheet->employee?->department?->name ?? 'N/A' }}</td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ $salarySheet->employee?->bank_account_no ?? 'N/A' }}
                                        </td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ $salarySheet->employee?->bank_name ?? 'N/A' }}</td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ \Carbon\Carbon::parse($salarySheet->salaryGenerate->salary_month)->format('F Y') }}
                                        </td>
                                    </tr>
                                    <br>
                                    <tr>
                                        <th colspan="2" class="header-row">Worked Days</th>
                                        <th colspan="2" class="header-row textLeftPadding">Overtime Hours</th>
                                        <th colspan="2" class="header-row textLeftPadding">LOP Days</th>
                                        <th colspan="2" class="header-row textLeftPadding">Payment Date</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">{{ number_format($workedDays, 2) ?? 'N/A' }} </td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ number_format($salarySheet->overtime_hours ?? 0, 2) }}</td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ number_format($lopDays, 2) ?? 'N/A' }} </td>
                                        <td colspan="2" class="textLeftPadding">
                                            {{ \Carbon\Carbon::parse($salarySheet->salaryGenerate->salary_month)->day . \Carbon\Carbon::parse($salarySheet->salaryGenerate->salary_month)->format('S F Y') }}
                                        </td>
                                    </tr>
                                </table>

                                <table class="salary_info">
                                    <tr>
                                        <th colspan="2" class="header-row">Description</th>
                                        <th class="header-row earnings-column">Earnings</th>
                                        <th class="header-row deductions-column">Deductions</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Basic Pay</td>
                                        <td class="earnings-column text-right">
                                            {{ number_format($salarySheet->employee->basic_salary ?? 0, 2) }}</td>
                                        <td class="deductions-column"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">HRA</td>
                                        <td class="earnings-column text-right">
                                            {{ number_format($salarySheet->total_allowances ?? 0, 2) }}</td>
                                        <td class="deductions-column"></td>
                                    </tr>
                                    @foreach ($allowances as $allowance)
                                        <tr>
                                            <td colspan="2" class="textLeftPadding">
                                                {{ $allowance['type'] }}
                                                <span
                                                    style="float: right;  padding: 0 5px;">{{ $allowance['total_amount'] }}</span>
                                            </td>
                                            <td class="earnings-column"></td>
                                            <td class="deductions-column"></td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2">Bonus Pay</td>
                                        <td class="earnings-column text-right">
                                            {{ number_format($salarySheet->total_bonus ?? 0, 2) }}</td>
                                        <td class="deductions-column"></td>
                                    </tr>
                                    {{-- <tr>
                                        <td colspan="2">Overtime Pay</td>
                                        <td class="earnings-column text-right">
                                            {{ number_format($salarySheet->total_overtimes ?? 0, 2) }}</td>
                                        <td class="deductions-column"></td>
                                    </tr> --}}
                                    <tr>
                                        <td colspan="2">Loss of Pay</td>
                                        <td class="earnings-column"></td>
                                        <td class="deductions-column text-right">
                                            {{ number_format($salarySheet->hourly_deduction ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Loan Repayment</td>
                                        <td class="earnings-column"></td>
                                        <td class="deductions-column text-right">
                                            {{ number_format($salarySheet->loan ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Advance Repayment</td>
                                        <td class="earnings-column"></td>
                                        <td class="deductions-column text-right">
                                            {{ number_format($salarySheet->salary_advance ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Other Deduction</td>
                                        <td class="earnings-column"></td>
                                        <td class="deductions-column text-right">
                                            {{ number_format($salarySheet->total_deduction ?? 0, 2) }}</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td colspan="2" class="text-right">Total</td>
                                        <td class="earnings-column text-right">
                                            {{ number_format($salarySheet->gross_salary - $salarySheet->total_overtimes, 2) }}
                                        </td>
                                        <td class="deductions-column text-right">
                                            {{ number_format($salarySheet->total_deduction + $salarySheet->salary_advance + $salarySheet->loan + $salarySheet->hourly_deduction, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="max-width: 150px; word-wrap: break-word;">
                                            <p><strong class="h5">Amount in Words</strong></p>
                                            <p class="text-break" style="font-size:12pt;">{{ ucfirst($words) }}</p>
                                        </td>
                                        <td colspan="2" style="text-align:center;">
                                            <p><Strong style="font-size:14pt;"> Net Pay</Strong></p>
                                            <p style="font-size:12pt;line-height:10px;">(Earnings-Deductions)</p>
                                            <p><Strong style="font-size:14pt;line-height:10px;">
                                                    {{ number_format($salarySheet->net_salary ?? 0, 2) }}</Strong></p>
                                        </td>

                                    </tr>


                                </table>
                                <table
                                    style="margin: 0 auto; text-align: center; width: 100%; table-layout: fixed;margin-top:70px;">
                                    <tr>
                                        <td style="padding: 10px;">Prepared By ________________</td>
                                        <td style="padding: 10px;">Approved By ________________</td>
                                        <td style="padding: 10px;">Received By ________________</td>
                                    </tr>
                                </table>

                            </div>

                        </div>

                        <br><br>
                        @can('export_employee_salaries')
                            <div class="row justify-content-end">
                                <div class="col-8">
                                    <input type="hidden" id="employee-id" value="{{ $salarySheet->employee->id }}">
                                    <input type="hidden" id="salary-month"
                                        value="{{ $salarySheet->salaryGenerate->salary_month }}">
                                    <div class="btn-group-custom">
                                        <a class="btn btn-warning m-1" target="_blank" id="btnPay"
                                            data-salary-id="{{ $salarySheet->id }}">Paid

                                            @if ($salarySheet->salaryPayment)
                                                <i class="fa fa-edit"></i>
                                            @endif
                                        </a>

                                        @can('export_employee_salaries')
                                            <a href="{{ route('employee-salaries.payslip.download-view', ['salarySheet' => $salarySheet->id]) }}"
                                                class="btn btn-info m-1" target="_blank">Print</a>
                                            <a href="{{ route('employee-salaries.payslip.download', ['salarySheet' => $salarySheet->id]) }}"
                                                class="btn btn-success m-1" target="_blank">Generate PDF</a>
                                            @if ($salarySheet->total_overtimes > 0)
                                                <a href="{{ route('employee-salaries.payslip.download-view', ['salarySheet' => $salarySheet->id, 'overtime_status' => 1]) }}"
                                                    class="btn btn-danger m-1" target="_blank">Print (Overtime Only)</a>
                                                <a href="{{ route('employee-salaries.payslip.download', ['salarySheet' => $salarySheet->id, 'overtime_status' => 1]) }}"
                                                    class="btn btn-danger m-1" target="_blank"> PDF (Overtime Only)</a>
                                            @endif
                                        @endcan


                                        {{-- <button class="btn btn-info m-1" onclick="printPayslip();">Print</button>
                                    <button class="btn btn-success m-1" id="generate-pdf">Generate PDF</button> --}}
                                    </div>
                                </div>
                            </div>
                        @endcan

                    </div>
                    <!-- Buttons Section -->


                </div>
            </div>
        </div>
    </section>
    @include('employee_salaries.pay')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            // Show modal and set salary ID when "Paid" button is clicked
            $(document).on('click', '#btnPay', function() {
                const salaryId = $(this).data('salary-id');
                $('#salaryId').val(salaryId); // Set hidden input value
                $('#payModal').modal('show'); // Show modal
            });

            // Show or hide bank options based on payment method selection
            $(document).on('change', '#paymentMethod', function() {
                if ($(this).val() === 'bank') {
                    $('#bankOptions').removeClass('d-none'); // Show bank select
                } else {
                    $('#bankOptions').addClass('d-none'); // Hide bank select
                }
            });

            // Handle form submission
            $('#payForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                startLoader();
                console.log(formData);
                // Example: Perform an AJAX request to submit the form
                $.ajax({
                    url: '{{ route('employee-salaries.pay-salary') }}', // Your route URL
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        stopLoader();
                        displaySuccessMessage(response.message);
                        $('#payModal').modal('hide'); // Close modal
                        location.reload();
                    },
                    error: function(xhr) {
                        stopLoader();
                        const errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        $.each(errors, function(key, value) {
                            errorMsg += value + '\n';
                        });

                        displayErrorMessage(errorMsg);

                    }
                });
            });
        });
    </script>
@endsection
