<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'dob',
        'gender',
        'phone',
        'address',
        'email',
        'password',
        'employee_id',
        'branch_id',
        'department_id',
        'designation_id',
        'company_doj',
        'documents',
        'account_holder_name',
        'account_number',
        'bank_name',
        'bank_identifier_code',
        'branch_location',
        'tax_payer_id',
        'UAN_NO',
        'salary_type',
        'annual_salary',
        'account',
        'salary',
        'created_by',
    ];

    public function documents()
    {
        return $this->hasMany('App\Models\EmployeeDocument', 'employee_id', 'employee_id')->get();
    }

    public function salary_type()
    {
        return $this->hasOne('App\Models\PayslipType', 'id', 'salary_type')->pluck('name')->first();
    }

    public function allowances()
    {
        return $this->hasMany(Allowance::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function saturationDeductions()
    {
        return $this->hasMany(SaturationDeduction::class);
    }

    public function otherPayments()
    {
        return $this->hasMany(OtherPayment::class);
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
    }

    public function get_net_salary()
{
    
    // Calculate total allowances
    $total_allowance = $this->allowances->sum(function ($allowance) {
        return ($allowance->type === 'fixed') ? $allowance->amount : ($allowance->amount * $this->salary / 100);
    });

    // Calculate total commissions
    $total_commission = $this->commissions->sum(function ($commission) {
        return ($commission->type === 'fixed') ? $commission->amount : ($commission->amount * $this->salary / 100);
    });

    // Calculate total loans
    $total_loan = $this->loans->sum(function ($loan) {
        return ($loan->type === 'fixed') ? $loan->amount : ($loan->amount * $this->salary / 100);
    });

    // Calculate total saturation deductions
    $total_saturation_deduction = $this->saturationDeductions->sum(function ($deduction) {
        return ($deduction->type === 'fixed') ? $deduction->amount : ($deduction->amount * $this->salary / 100);
    });

    // Calculate total other payments
    $total_other_payment = $this->otherPayments->sum(function ($otherPayment) {
        return ($otherPayment->option === 'allowance') ? $otherPayment->amount : ($otherPayment->amount * $this->salary / 100);
    });
    $total_other_payment_deduction = $this->otherPayments->sum(function ($otherPayment) {
        return ($otherPayment->option === 'deduction') ? $otherPayment->amount : ($otherPayment->amount * $this->salary / 100);
    });

    
    // Calculate total overtime
    $total_over_time = $this->overtimes->sum(function ($over_time) {
        return $over_time->number_of_days * $over_time->hours * $over_time->rate;
    });

    // Calculate net salary
    $net_salary = $this->salary + $total_allowance + $total_commission - $total_loan - $total_saturation_deduction -$total_other_payment_deduction + $total_other_payment + $total_over_time;

    return $net_salary;
}
    // public function get_net_salary()
    // {

    //     //allowance
    //     $allowances      = Allowance::where('employee_id', '=', $this->id)->get();
    //     $total_allowance = 0 ;
    //     foreach($allowances as $allowance)
    //     {
    //         if($allowance->type == 'fixed')
    //         {
    //             $totalAllowances  = $allowance->amount;
    //         }
    //         else
    //         {
    //             $totalAllowances  = $allowance->amount * $this->salary / 100;
    //         }
    //         $total_allowance += $totalAllowances ;
    //     }

    //     //commission
    //     $commissions      = Commission::where('employee_id', '=', $this->id)->get();
    //     $total_commission = 0;
    //     foreach($commissions as $commission)
    //     {
    //         if($commission->type == 'fixed')
    //         {
    //             $totalCom  = $commission->amount;
    //         }
    //         else
    //         {
    //             $totalCom  = $commission->amount * $this->salary / 100;
    //         }
    //         $total_commission += $totalCom ;
    //     }

    //     //Loan
    //     $loans      = Loan::where('employee_id', '=', $this->id)->get();
    //     $total_loan = 0;
    //     foreach($loans as $loan)
    //     {
    //         if($loan->type == 'fixed')
    //         {
    //             $totalloan  = $loan->amount;
    //         }
    //         else
    //         {
    //             $totalloan  = $loan->amount * $this->salary / 100;
    //         }
    //         $total_loan += $totalloan ;
    //     }


    //     //Saturation Deduction
    //     $saturation_deductions      = SaturationDeduction::where('employee_id', '=', $this->id)->get();
    //     $total_saturation_deduction = 0 ;
    //     foreach($saturation_deductions as $deductions)
    //     {
    //         if($deductions->type == 'fixed')
    //         {
    //             $totaldeduction  = $deductions->amount;
    //         }
    //         else
    //         {
    //             $totaldeduction  = $deductions->amount * $this->salary / 100;
    //         }
    //         $total_saturation_deduction += $totaldeduction ;
    //     }

    //     //OtherPayment
    //     $other_payments      = OtherPayment::where('employee_id', '=', $this->id)->get();
    //     $total_other_payment = 0;
    //     $total_other_payment = 0 ;
    //     foreach($other_payments as $otherPayment)
    //     {
    //         if($otherPayment->type == 'fixed')
    //         {
    //             $totalother  = $otherPayment->amount;
    //         }
    //         else
    //         {
    //             $totalother  = $otherPayment->amount * $this->salary / 100;
    //         }
    //         $total_other_payment += $totalother ;
    //     }

    //     //Overtime
    //     $over_times      = Overtime::where('employee_id', '=', $this->id)->get();
    //     $total_over_time = 0;
    //     foreach($over_times as $over_time)
    //     {
    //         $total_work      = $over_time->number_of_days * $over_time->hours;
    //         $amount          = $total_work * $over_time->rate;
    //         $total_over_time = $amount + $total_over_time;
    //     }


    //     //Net Salary Calculate
    //     $advance_salary = $total_allowance + $total_commission - $total_loan - $total_saturation_deduction + $total_other_payment + $total_over_time;

    //     $employee       = Employee::where('id', '=', $this->id)->first();

    //     $net_salary     = (!empty($employee->salary) ? $employee->salary : 0) + $advance_salary;

    //     return $net_salary;

    // }

    public static function allowance($id)
    {

        //allowance
        $allowances      = Allowance::where('employee_id', '=', $id)->get();
        $total_allowance = 0;
        foreach($allowances as $allowance)
        {
            $total_allowance = $allowance->amount + $total_allowance;
        }

        $allowance_json = json_encode($allowances);

        return $allowance_json;

    }

    public static function commission($id)
    {
        //commission
        $commissions      = Commission::where('employee_id', '=', $id)->get();
        $total_commission = 0;
        foreach($commissions as $commission)
        {
            $total_commission = $commission->amount + $total_commission;
        }
        $commission_json = json_encode($commissions);

        return $commission_json;

    }

    public static function loan($id)
    {
        //Loan
        $loans      = Loan::where('employee_id', '=', $id)->get();
        $total_loan = 0;
        foreach($loans as $loan)
        {
            $total_loan = $loan->amount + $total_loan;
        }
        $loan_json = json_encode($loans);

        return $loan_json;
    }

    public static function saturation_deduction($id)
    {
        //Saturation Deduction
        $saturation_deductions      = SaturationDeduction::where('employee_id', '=', $id)->get();
        $total_saturation_deduction = 0;
        foreach($saturation_deductions as $saturation_deduction)
        {
            $total_saturation_deduction = $saturation_deduction->amount + $total_saturation_deduction;
        }
        $saturation_deduction_json = json_encode($saturation_deductions);

        return $saturation_deduction_json;

    }

    public static function other_payment($id,$month_year)
    {
        //OtherPayment
        $other_payments      = OtherPayment::where('employee_id', '=', $id)->where('year_month','=',$month_year)->get();
        $total_other_payment = 0;
        foreach($other_payments as $other_payment)
        {
            $total_other_payment = $other_payment->amount + $total_other_payment;
        }
        $other_payment_json = json_encode($other_payments);

        return $other_payment_json;
    }

    public static function overtime($id)
    {
        //Overtime
        $over_times      = Overtime::where('employee_id', '=', $id)->get();
        $total_over_time = 0;
        foreach($over_times as $over_time)
        {
            $total_work      = $over_time->number_of_days * $over_time->hours;
            $amount          = $total_work * $over_time->rate;
            $total_over_time = $amount + $total_over_time;
        }
        $over_time_json = json_encode($over_times);

        return $over_time_json;
    }

    public static function employee_id()
    {
        $employee = Employee::latest()->first();

        return !empty($employee) ? $employee->id + 1 : 1;
    }

    public function branch()
    {
        return $this->hasOne('App\Models\Branch', 'id', 'branch_id');
    }

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }

    public function designation()
    {
        return $this->hasOne('App\Models\Designation', 'id', 'designation_id');
    }

    public function salaryType()
    {
        return $this->hasOne('App\Models\PayslipType', 'id', 'salary_type');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function paySlip()
    {
        return $this->hasOne('App\Models\PaySlip', 'id', 'employee_id');
    }

    public function bankAccount()
    {
        return $this->hasOne('App\Models\BankAccount', 'id', 'account');
    }


    public function present_status($employee_id, $data)
    {
        return AttendanceEmployee::where('employee_id', $employee_id)->where('date', $data)->first();
    }


    public static function employee_salary($salary)
    {
        $employee = Employee::where("salary", $salary)->first();
        if ($employee->salary == '0' || $employee->salary == '0.0') {
            return "-";
        } else {
            return $employee->salary;
        }
    }

    public static function set_salary($CTC,$id)
    {
        $CTC=$CTC;
        if((($CTC*25)/100) > 180000){
            $basicSal = ($CTC*25)/100;
            $MonthlybasicSal=$basicSal/12;
          }
          else{
            $basicSal = 180000;
            $MonthlybasicSal=$basicSal/12;
          }
    
          // HRA = 30% of Basic Sal
          $HRA = ($basicSal * 30) / 100;
          $MonthlyHRA=$HRA/12;
          // Fixed Allowance
          $fixedAllow = $CTC - $basicSal - $HRA;
          $MonthlyfixedAllow=round($fixedAllow/12);
          
    
         // $PF1 = ($basicSal*12)/100;
         $PF1 = 21600;
          $MonthlyPF1=$PF1/12;
         // $PF2 = ($basicSal*12)/100;
         $PF2 = 21600;
          $MonthlyPF2=$PF2/12;
          $PT = 2400;
          $MonthlyPT=($PT / 12);
          $insurance = 3000;
          $Monthlyinsurance=$insurance / 12;
          $gratuity = ($basicSal*4.81)/100;
          $MonthlyGratuity = round($gratuity/12);
          $allowance=AllowanceOption::get();
          foreach($allowance as $allowance)
          {
            $all_opt=Allowance::where('allowance_option',$allowance->id)->where('employee_id',$id)->first();
           
            if(empty($all_opt))
            {
                $allo_insert = new Allowance();
                if($allowance->name=='HRA')
                {
                    $allo_insert->amount=$MonthlyHRA;
                }else if($allowance->name=="Fixed Allowance")
                {
                    $allo_insert->amount=$MonthlyfixedAllow;
                }
               
                $allo_insert->employee_id=$id;
                $allo_insert->type='fixed';
                $allo_insert->allowance_option=$allowance->id;
                $allo_insert->save();
            }else{
                $allo_insert = Allowance::find($all_opt->id);
              
                if($allowance->name=='HRA')
                {
                    $allo_insert->amount=$MonthlyHRA;
                }else if($allowance->name=="Fixed Allowance")
                {
                    $allo_insert->amount=$MonthlyfixedAllow;
                }
                $allo_insert->save();
                
            }
          }


          $deduction=DeductionOption::get();
          foreach($deduction as $deduction)
          {
            $dedu_opt=SaturationDeduction::where('deduction_option',$deduction->id)->where('employee_id',$id)->first();
           
            if(empty($dedu_opt))
            {
                $dedu_insert = new SaturationDeduction();
                if($deduction->name=='PF Employee Contribution')
                {
                    $dedu_insert->amount=$MonthlyPF1;
                }else if($deduction->name=="PF Employeer Contribution")
                {
                    $dedu_insert->amount=$MonthlyPF2;
                }else if($deduction->name=="Professional Tax")
                {
                    $dedu_insert->amount=$MonthlyPT;
                }else if($deduction->name=="Insuarance")
                {
                    $dedu_insert->amount=$Monthlyinsurance;
                }else if($deduction->name=="Gratuity")
                {
                    $dedu_insert->amount=$MonthlyGratuity;
                }
                $dedu_insert->employee_id=$id;
                $dedu_insert->type='fixed';
                $dedu_insert->deduction_option=$deduction->id;
                $dedu_insert->save();
            }else{
                $dedu_insert = SaturationDeduction::find($dedu_opt->id);
                if($deduction->name=='PF Employee Contribution')
                {
                    $dedu_insert->amount=$MonthlyPF1;
                }else if($deduction->name=="PF Employeer Contribution")
                {
                    $dedu_insert->amount=$MonthlyPF2;
                }else if($deduction->name=="Professional Tax")
                {
                    $dedu_insert->amount=$MonthlyPT;
                }else if($deduction->name=="Insuarance")
                {
                    $dedu_insert->amount=$Monthlyinsurance;
                }else if($deduction->name=="Gratuity")
                {
                    $dedu_insert->amount=$MonthlyGratuity;
                }
                $dedu_insert->save();
                
            }
          }
       
    }

    public static function salary_generation($month,$year,$formate_month_year,$employee_id,$basic_salary)
    {
        $date = Carbon::createFromFormat('Y-m', $formate_month_year);
        $daysInMonth = $date->daysInMonth;
        // Get the start and end dates for the month
        $start_date = $date->startOfMonth()->toDateString();
        $end_date = $date->endOfMonth()->toDateString();
        $resignation = Resignation::where('employee_id', '=' , $employee_id)->first();
        
        // Query to get the total leave count
            $leaveCount = AttendanceEmployee::where('employee_id', $employee_id)
            ->whereBetween('date', [$start_date, $end_date])
            ->sum('day_count');
            $get_month_leave_count = LeaveMapping::whereMonth('leave_date', $month)
            ->whereYear('leave_date', $year)
            ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
            ->where('leaves.employee_id',$employee_id)
            ->where('leave_mappings.leave_type','Paid')
          
            ->sum('leave_mappings.leave_count');
            $check_doj=Employee::where('id', $employee_id)->first();
if($resignation)
{

    $get_month_holiday_count=Holiday::whereMonth('date',$month)->whereYear('date',$year)
    ->where('date','>=',$check_doj->company_doj)
   
      ->orWhere('date','<=',$resignation->resignation_date)
   
    ->whereNotIn('date', function ($query) use ($employee_id, $month, $year) {
      $query->select('leave_date')
          ->from('leave_mappings')
          ->join('leaves', 'leaves.id', '=', 'leave_mappings.leave_id')
          ->where('leaves.employee_id', $employee_id)
          ->whereMonth('leave_date', $month)
          ->whereYear('leave_date', $year);
  })
    ->count();
}else{

    $get_month_holiday_count=Holiday::whereMonth('date',$month)->whereYear('date',$year)
    ->where('date','>=',$check_doj->company_doj)
   
    ->whereNotIn('date', function ($query) use ($employee_id, $month, $year) {
      $query->select('leave_date')
          ->from('leave_mappings')
          ->join('leaves', 'leaves.id', '=', 'leave_mappings.leave_id')
          ->where('leaves.employee_id', $employee_id)
          ->whereMonth('leave_date', $month)
          ->whereYear('leave_date', $year);
  })
    ->count();
}

      
       $present_days=$leaveCount+$get_month_leave_count+$get_month_holiday_count; //Attendance count

     
       /********** */
       $actual_basic_salary=round($basic_salary/$daysInMonth*$present_days,0);
       /*************For Allowance************** */
       $actual_allowance = [];

       $fixed_allowance_json=Employee::allowance($employee_id);
    
       $fixed_allowance = json_decode($fixed_allowance_json, true); 
       $gross_total=0;
       $total=0;
       foreach($fixed_allowance  as $fixed_allowance)
       {
           $allowance_option=AllowanceOption::where('id',$fixed_allowance['allowance_option'])->first();
         
           if($allowance_option->name=='HRA')
        {
           $allowance_amount=round($fixed_allowance['amount']/$daysInMonth*$present_days,0);
           $actual_hra=$allowance_amount;
          
        }else if($allowance_option->name=="Fixed Allowance")
        {
            $allowance_amount=round($fixed_allowance['amount']/$daysInMonth*$present_days,0);
            $actual_fixed_all=$allowance_amount;
           
        }
       
        $actual_allowance[]=[
        "id"=>$fixed_allowance['id'],
        "employee_id"=>$fixed_allowance['employee_id'],
        "allowance_option"=>$fixed_allowance['allowance_option'],
        "title"=>$allowance_option->name,
        "amount"=>$allowance_amount,
        "type"=>$fixed_allowance['type']
        ];
        $gross_total +=$allowance_amount;
        $total +=round($fixed_allowance['amount'],0);
       }
       $gross_total += $actual_basic_salary;
       $total +=$basic_salary;
       /**********Allowance End********** */
       /************For Deduction********* */
       $actual_deduction = [];
       $actual_total_deduction =0;
       $fixed_saturation_deduction_json=Employee::saturation_deduction($employee_id);
       $fixed_saturation_deduction = json_decode($fixed_saturation_deduction_json, true);
       foreach($fixed_saturation_deduction  as $fixed_saturation_deduction)
       {
          $deduction_option=DeductionOption::where('id',$fixed_saturation_deduction['deduction_option'])->first();
        
            if($deduction_option->name=='PF Employee Contribution')
                {
                  if( ($gross_total-$actual_hra)>='15000')
                  {
                    $deduction_amount=round($fixed_saturation_deduction['amount'],0);
                  }else{
                    $deduction_amount=round(($gross_total-$actual_hra)*0.12,0);
                  }
                 
                    
                }else if($deduction_option->name=="PF Employeer Contribution")
                {
                    if( ($gross_total-$actual_hra)>='15000')
                    {
                      $deduction_amount=round($fixed_saturation_deduction['amount'],0);
                    }else{
                      $deduction_amount=round(($gross_total-$actual_hra)*0.12,0);
                    }
                }else if($deduction_option->name=="Professional Tax")
                {
                    if($month=='02')
                    {
                        $deduction_amount='300';
                    }else{
                        $deduction_amount=round($fixed_saturation_deduction['amount'],0);
                    }
                    
                }else if($deduction_option->name=="Insuarance")
                {
                    $deduction_amount=round($fixed_saturation_deduction['amount'],0);
                  
                }else if($deduction_option->name=="Gratuity")
                {
                    $deduction_amount=round(($actual_basic_salary*4.81/100),0);
                    
                }
                $actual_total_deduction +=$deduction_amount;
        $actual_deduction[]=[
        "id"=>$fixed_saturation_deduction['id'],
        "employee_id"=>$fixed_saturation_deduction['employee_id'],
        "deduction_option"=>$fixed_saturation_deduction['deduction_option'],
        "title"=>$deduction_option->name,
        "amount"=>$deduction_amount,
        "type"=>$fixed_saturation_deduction['type']
        ];
       }

       /**********Deduction End*********** */
       
       $other_payments      = OtherPayment::where('employee_id', '=', $employee_id)->where('year_month','=',$formate_month_year)->get();
       $total_other_payment = 0;
       if(!empty($other_payments))
       {
       foreach($other_payments as $other_payment)
       {
        if($other_payment->payment_option=='deduction')
        {
            $total_other_payment =$total_other_payment-$other_payment->amount;  
        }else{
            $total_other_payment =$total_other_payment+$other_payment->amount;
        }
         
       }
    }

    $net_payble= $gross_total-$actual_total_deduction+$total_other_payment;
       $employee_salary=[
        "total_days"=>$daysInMonth,
        "present_days"=>$present_days,
        "gross_salary"=>$gross_total,
        "actual_basic_salary"=>$actual_basic_salary,
        "total"=>$total,
        "net_payble"=>$net_payble,
        "actual_allowance"=>json_encode($actual_allowance),
        "actual_deduction"=>json_encode($actual_deduction),
       ];
      
       return $employee_salary;
    }




}
