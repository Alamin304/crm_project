<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Models\SalaryGenerate;
use App\Models\Employee;
use App\Models\SalarySheet;
use App\Models\SalaryAdvance;
use App\Models\Loan;
use App\Models\Deduction;
use App\Models\Allowance;
use App\Models\Bonus;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Class CustomerRepository
 *
 * @version April 3, 2020, 6:37 am UTC
 */
class SalaryGenerateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'salary_month',
        'generate_date',
        'generated_by',
        'approved_by',
        'status',
        'approved_date'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SalaryGenerate::class;
    }

    public function create($input)
    {
        try {
            return DB::transaction(function () use ($input) {
                $salaryMonth = $input['salary_month'];
                $branchId = $input['branch_id'];

                // Check if a record with the same salary_month, branch_id, and status = 1 exists
                $existingApproved = SalaryGenerate::where('salary_month', $salaryMonth)
                    ->where('branch_id', $branchId)
                    ->where('status', 1)
                    ->exists();

                if ($existingApproved) {

                    return false;
                }

                // Check if a record with the same salary_month and branch_id but status = 0 exists
                $existingPending = SalaryGenerate::where('salary_month', $salaryMonth)
                    ->where('branch_id', $branchId)
                    ->where('status', 0)
                    ->first();

                if ($existingPending) {
                    // Delete related salary sheets first
                    $existingPending->salarySheets()->delete();
                    // Then delete the salary generate record
                    $existingPending->delete();
                }

                // Set default values
                $input['status'] = 0;
                $input['approved_date'] = now();

                // Create a new record
                return SalaryGenerate::create(Arr::only($input, [
                    'salary_month',
                    'generate_date',
                    'generated_by',
                    'approved_by',
                    'status',
                    'approved_date',
                    'branch_id'
                ]));
            });
        } catch (Exception $e) {

            return false;
        }
    }
    public function  create_salary($salary_sheets)
    {
        SalarySheet::insert($salary_sheets);
    }

    public function approve_salary(SalaryGenerate $salaryGenerate)
    {
        $salaryGenerate->status = 1;
        $salaryGenerate->save();
        return $salaryGenerate;
    }

    public function generateSalary($month)
    {

        $settings = Setting::pluck('value', 'key')->toArray();
        $overTimeRate = isset($settings['overtime_rate'])
            ? (float) $settings['overtime_rate'] / 100
            : 1.0;

        $salaryAmount = 0;
        $salaryGenerateId = $month->id;
        $branch_id = $month->branch_id;
        // Define working hours per day and weekend days (friday and sunday as weekend)
        $workingHoursPerDay = 8;
        $weekendDays = ['friday']; // Specify weekend days

        $salaryMonth = $month['salary_month']; // Example: '2024-11'
        $year = substr($salaryMonth, 0, 4); // Extract year
        $monthNum = substr($salaryMonth, 5, 2); // Extract month

        // Get all employees along with their attendance data for the specified month
        $employees = Employee::with([
            'attendance' => function ($query) use ($year, $monthNum) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $monthNum);
            },
            // 'allowances' => function ($query) use ($year, $monthNum) {
            //     $query->whereYear('date', $year)
            //         ->whereMonth('date', $monthNum);
            // },
            'allowances',
            'deductions' => function ($query) use ($year, $monthNum) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $monthNum);
            },
            'advances' => function ($query) use ($year, $monthNum) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $monthNum);
            },
            'loans' => function ($query) use ($year, $monthNum) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $monthNum);
            },
            'bonuses' => function ($query) use ($year, $monthNum) {
                $query->whereYear('date', $year)
                    ->whereMonth('date', $monthNum);
            },
        ])->where('branch_id', $branch_id)->where('status', 1)->get();


        $salary_sheets = [];

        // Iterate through each employee
        foreach ($employees as $employee) {
            // Base salary of the employee (assumed gross salary column)
            $gross_salary = $employee->gross_salary;

            // Calculate total allowances for the specified month
            $totalAllowances = $employee->allowances->sum('amount');
            $totalDeductions = $employee->deductions->sum('amount');
            $totalAdvances = $employee->advances->sum('installment');
            $totalLoans = $employee->loans->sum('installment');
            $totalBonuses = $employee->bonuses->sum('amount');
            $total_overtimes = 0;
            $absense_hours = 0;
            $overtimeHours = 0;

            // Calculate total working hours in the month (excluding weekend days)
            $workingHours = 0;
            $workingDaysCount = 0;

            // Loop through attendance records and calculate working hours
            foreach ($employee->attendance as $attendance) {
                $attendanceDate = $attendance['date'];
                $dayOfWeek = date('l', strtotime($attendanceDate)); // Get day of the week (e.g., 'Monday', 'Friday')

                // Check if it's not a weekend day (in the $weekendDays array)
                if (!in_array(strtolower($dayOfWeek), $weekendDays)) {
                    $workingHours += $attendance['hours'];
                    $workingDaysCount++;
                }
            }

            // Calculate basic salary based on working days and working hours per day
            $totalWorkingHoursThisMonth = ($workingDaysCount * $workingHoursPerDay); // 8 hours per working day
            // Avoid division by zero by checking if total working hours are greater than 0
            if ($totalWorkingHoursThisMonth > 0) {
                $hourlyRate = $employee->basic_salary / $totalWorkingHoursThisMonth;
            } else {
                $hourlyRate = 0; // Default to 0 to avoid division by zero
            }
            $calculatedSalary = $workingHours * $hourlyRate;
            $hourlyDeduction = 0;

            // if ($employee->basic_salary > $calculatedSalary) {
            // }




            if ($workingHours > $totalWorkingHoursThisMonth) {
                $overtimeHours = $workingHours - $totalWorkingHoursThisMonth;
                $total_overtimes = $overtimeHours * $hourlyRate * $overTimeRate;
            } else if ($workingHours == $totalWorkingHoursThisMonth) {
                $absense_hours = 0;
                $hourlyDeduction = 0;
            } else {
                $absense_hours = $totalWorkingHoursThisMonth - $workingHours;
                $hourlyDeduction = $employee->basic_salary - $calculatedSalary;
            }


            // echo $workingHours . " " . $totalWorkingHoursThisMonth . " " . $hourlyDeduction;
            // echo "\n";
            // Additional fields for the new structure
            $totalCommission = 0; // Replace with the actual commission calculation if available
            $totalInsurance = 0;  // Replace with the actual insurance calculation if available
            $totalLoan = $totalLoans;
            $totalBonus = $totalBonuses;
            $totalAllowance = $totalAllowances;
            $totalDeduction = $totalDeductions;


            $netSalary = $employee->basic_salary + $totalAllowance + $totalBonus - $totalDeduction - $totalAdvances - $totalLoan
                -   $hourlyDeduction
                + $total_overtimes;
            $gross_salary = $employee->basic_salary + $totalAllowance + $totalBonus
                + $total_overtimes;
            // Structure the data to match the desired output
            $tmp = [
                'employee_id' => $employee->id,
                'salary_generate_id' => $salaryGenerateId,
                'basic_salary' =>  $employee->basic_salary,
                'salary_advance' => $totalAdvances,
                'gross_salary' => $gross_salary,
                'state_income_tax' => 0, // Adjust this if there's a tax calculation
                'loan' => $totalLoan,
                'total_bonus' => $totalBonus,
                'total_allowances' => $totalAllowance,
                'total_commission' => $totalCommission,
                'total_insurance' => $totalInsurance,
                'total_deduction' => $totalDeduction,
                'net_salary' => $netSalary,
                'hourly_deduction'  => $hourlyDeduction ?? 0,
                'total_overtimes' => $total_overtimes,
                'overtime_hours' => $overtimeHours ?? 0,
                'absence_hours' => $absense_hours,
                'worked_hours' => $workingHours ?? 0,
                'working_hours' => $totalWorkingHoursThisMonth ?? 0,
                'branch_id' => $branch_id
            ];


            $salaryAmount += $netSalary;
            // Add to the salary sheets array
            array_push($salary_sheets, $tmp);
        }

        // dd($salary_sheets);

        SalarySheet::insert($salary_sheets);

        SalaryGenerate::where('id', $salaryGenerateId)  // Ensure 'id' is provided to identify the record
            ->update(['amount' => round($salaryAmount, 2)]);



        $salary_sheets_with_employees = collect($salary_sheets)->map(function ($sheet) {
            $employee = Employee::with(['designation'])->find($sheet['employee_id']); // Fetch employee data
            $sheet['employee'] = $employee; // Attach employee data to the sheet
            return $sheet;
        });

        return $salary_sheets_with_employees;
    }
}
