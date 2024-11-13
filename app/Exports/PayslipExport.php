<?php

namespace App\Exports;

use App\Models\AllowanceOption;
use App\Models\Employee;
use App\Models\PaySlip;
use App\Models\LeaveType;
use App\Models\LeaveMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;
class PayslipExport implements FromCollection, WithHeadings, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $request = $this->data;



        if (isset($request->filter_month) && !empty($request->filter_month)) {
            $month = $request->filter_month;
        } else {
            $month = date('m', strtotime('last month'));
        }

        if (isset($request->filter_year) && !empty($request->filter_year)) {
            $year = $request->filter_year;
        } else {
            $year = date('Y');
        }
        $formate_month_year = $year . '-' . $month;
        $data = PaySlip::where('salary_month', '=', $formate_month_year)
        ->orderBy('employee_id', 'asc')
        ->get();
        $result = array();
        $i = 1;

        $totals = [
            'sr_no'=>'',
            'employee_name'=>'',
            'employee_id'=>'',
            'dob'=>'',
            'doj'=>'',
            'total_days'=>0,
            'present_days'=>0,
            'basic_salary' => 0,
            'hra' => 0,
            'CON' => 0,
            'Total' => 0,
            'actual_basic' => 0,
            'actual_hra' => 0,
            'actual_con' => 0,
            'gross_total' => 0,
            'PT' => 0,
            'PF(Employee)' => 0,
            'ESIC' => 0,
            'LWF'=>0,
            'OTHER DED'=>0,
            'IT'=>0,
            'PF(Employer)' => 0,
            'Insurance' => 0,
            'Gratuity' => 0,
            'total_ded' => 0,
            'other_allowance'=>0,
            'Reimbursement'=>0,
            'net_salary' => 0,
        ];

        foreach ($data as $k => $payslip) {
            $allowance = json_decode($payslip->allowance);
            $HRA = 0;
            $CON = 0;
            foreach ($allowance as $all) {
                $option = AllowanceOption::where('id', '=', $all->allowance_option)->first();
                if ($option->name == 'HRA') {
                    $HRA = $all->amount;
                } else if ($option->name == 'Fixed Allowance') {
                    $CON = $all->amount;
                }
            }
            $total = round($payslip->basic_salary + $HRA + $CON,0);

            $actual_allowance = json_decode($payslip->actual_allowance);
            $actual_HRA = 0;
            $actual_CON = 0;
            foreach ($actual_allowance as $alll) {

                if ($alll->title == 'HRA') {
                    $actual_HRA = $alll->amount;
                } else if ($alll->title == 'Fixed Allowance') {
                    $actual_CON = $alll->amount;
                }
            }

            $deduction = json_decode($payslip->actual_saturation_deduction);
            $PT = 0;
            $PF1 = 0;
            $PF2=0;
            $Insurance=0;
            $Gratuity=0;
            $total_ded=0;
            foreach ($deduction as $ded) {

                if ($ded->title == "PF Employee Contribution") {
                    $PF1 = $ded->amount;
                } else if ($ded->title == "PF Employeer Contribution") {
                    $PF2 = $ded->amount;
                }else if($ded->title == "Professional Tax")
                {
                    $PT =$ded->amount;
                }else if($ded->title == "Gratuity")
                {
                    $Gratuity=$ded->amount;
                }else if($ded->title == "Insuarance")
                {
                    $Insurance=$ded->amount;
                }
            }
           
            $other_payments = json_decode($payslip->other_payment);
            $total_ded_other_payment = 0;
            $total_LWF_ded=0;
            $total_Reimbursement=0;
            $total_allow_other_payment=0;
            if(!empty($other_payments))
            {
            foreach($other_payments as $other_payment)
            {
             if($other_payment->payment_option=='deduction')
             {
                if($other_payment->title=='LWF')
                {
                    $total_LWF_ded +=$other_payment->amount;  
                }else{
                    $total_ded_other_payment +=$other_payment->amount;  
                }
                
             }else if($other_payment->payment_option=='allowance')
             {
                if($other_payment->title=='Reimbursement')
                {
                    $total_Reimbursement +=$other_payment->amount;
                }else{
                $total_allow_other_payment +=$other_payment->amount;
                }  
             }
              
            }
         }
         $total_ded=round($PT+$PF1+$PF2+$Insurance+$Gratuity+$total_ded_other_payment+$total_LWF_ded,0);


         /*************Leave Details********** */
         $total_leave= LeaveType::where('leave_year',$year)->first();
         $total_paid_leave = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
         ->where('leave_mappings.leave_type','Paid')
        // ->where('leave_mappings.status','Approved')
         ->whereYear('leave_date', $year)
         
         ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
         ->where('leaves.employee_id',$payslip->employees->id)
         ->first();

         $total_current_Monthly_paid_leave = LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
         ->where('leave_mappings.leave_type','Paid')
        // ->where('leave_mappings.status','Approved')
         ->whereYear('leave_date', $year)
         ->whereMonth('leave_date', $month)
         ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check total leave for particular employee
         ->where('leaves.employee_id',$payslip->employees->id)
         ->first();
        // $available_paid_leaves=$leave_type_year->days-$total_paid_leave->leave_count;
        $doj = \Carbon\Carbon::parse($payslip->employees->company_doj);
       //  $current = \Carbon\Carbon::parse(date(''.$year.'-m-d'));
         if($year==date('Y'))
                               {
                               
                                 $current = \Carbon\Carbon::parse(date(''.$year.'-m-d'));
                               }else{
                              
                               $current = \Carbon\Carbon::createFromDate($year, 12, 31);
                               }
         $diffInYears = $current->diffInYears($doj);
        $diffInMonths = $current->diffInMonths($doj) % 12; // Get the remaining months after getting the years
        $differenceInyears = $diffInYears . '.' . $diffInMonths;
        $doj_month = date('m', strtotime($payslip->employees->company_doj)); 
        $one_year_doj = date('Y', strtotime($payslip->employees->company_doj))+1; 
         //$available_month=12-$doj_month;
         if( $doj->day <= 15)
         {
            $available_month=12-$doj_month+1;
         }else{
            $available_month=12-$doj_month;
         }
         if($differenceInyears<'1')
         {
             
             $available_paid_leaves=0;
         }else
         if($differenceInyears>='1' && $differenceInyears<'2' && $one_year_doj==$year)
         {
           
             $available=$available_month*($total_leave->days/12);
             $available_paid_leaves=$available-$total_paid_leave->leave_count;
         }else{
            $available=$total_leave->days;
             $available_paid_leaves=$total_leave->days-$total_paid_leave->leave_count;
         }

              /*************Logic for previous year avialable leave count and add to current or selected year************** */
              $get_leave_type =  \App\Models\LeaveType::where('leave_year',$year-1)->where('leave_paid_status','Paid')->first();
              $previous_year_pending_leave=0;
              if($get_leave_type )
              {
              
                  if($doj->year<=$get_leave_type->leave_year)
                  {
                  $select_previous_leave= \App\Models\LeaveMapping::selectRaw('SUM(leave_mappings.leave_count) as leave_count')
                  
                  ->whereYear('leave_date', $get_leave_type->leave_year)
                  ->join('leaves','leaves.id', '=','leave_mappings.leave_id' ) // for check monthly leave limit
                  ->where('leaves.employee_id',$payslip->employees->id)
                  ->where('leave_mappings.leave_type','Paid')
                  // ->where('leave_mappings.status','Approved')
                  ->first();
                   $previous_year_paid_leave =$select_previous_leave->leave_count;

                  if( $doj->day <= 15)
                  {
                      $available_month1=12-$doj_month+1;
                  }else{
                      $available_month1=12-$doj_month;
                  }
                   $current1 = \Carbon\Carbon::createFromDate( $get_leave_type->leave_year, 12, 31);
                  $diffInYears1 = $current1->diffInYears($doj);
                  $diffInMonths1 = $current1->diffInMonths($doj) % 12; // Get the remaining months after getting the years
                  $differenceInyears1 = $diffInYears1 . '.' . $diffInMonths1;
                 
                  if($differenceInyears1<'1')
              {
                   $leave_day_year1=0;
               
              }else
                  if($differenceInyears1>='1' && $differenceInyears1<'2')
                  {
                      
                   $leave_day_year1=$available_month1*($get_leave_type->days/12);
                      
                      
                  }else{
                      
                      $leave_day_year1=$get_leave_type->days;
                  }

                  $previous_year_pending_leave +=$leave_day_year1-$previous_year_paid_leave;
                  
                  }
            
              }
             $previous_year_pending_leave;
              /*******end previous year logic */
            $result[] = array(
                'sr_no' => $i,
                'employee_name' => (!empty($payslip->employees)) ? $payslip->employees->name : '',
                'employee_id' => !empty($payslip->employees) ? \Auth::user()->employeeIdFormat($payslip->employees->employee_id) : '',
                'dob' => (!empty($payslip->employees)) ? $payslip->employees->dob : '',
                'doj' => (!empty($payslip->employees)) ? $payslip->employees->company_doj : '',
                'total_days' => (!empty($payslip->employees)) ? $payslip->total_days : '',
                'present_days' => (!empty($payslip->employees)) ? $payslip->present_days : '',

                'basic_salary' => $payslip->basic_salary,
                'hra' => $HRA,
                'CON' => $CON,
                'Total' => $total,
                'actual_basic' => $payslip->actual_basic_salary,
                'actual_hra' => $actual_HRA,
                'actual_con' => $actual_CON,
               
                'gross_total'=>$payslip->gross_salary,
                'PT'=>$PT,
                'PF(Employee)'=>$PF1,
                'ESIC'=>0,
                'LWF'=>$total_LWF_ded,
                'OTHER DED'=>$total_ded_other_payment,
                'IT'=>0,
                'PF(Employer)'=>$PF2,
                'Insurance'=>$Insurance,
                'Gratuity'=>$Gratuity,
                'total_ded'=>$total_ded,
                'other_allowance'=>$total_allow_other_payment,
                'Reimbursement'=>$total_Reimbursement,
                'net_salary' =>$payslip->net_payble,
                'year_total_leave' =>$total_paid_leave->leave_count+$available_paid_leaves+$previous_year_pending_leave,
                'year_taken_leave' => $total_paid_leave->leave_count,
                'month_taken_leave'=>$total_current_Monthly_paid_leave->leave_count,
                'remain_leave' => $available_paid_leaves+$previous_year_pending_leave,
               
               
            );

        $totals['sr_no'] ='';
        $totals['employee_name']='';
        $totals['dob']='';
        $totals['doj']='';
        $totals['total_days'] +=$payslip->total_days;
        $totals['present_days'] +=$payslip->present_days;
        $totals['basic_salary'] += $payslip->basic_salary;
        $totals['hra'] += $HRA;
        $totals['CON'] += $CON;
        $totals['Total'] += $total;
        $totals['actual_basic'] += $payslip->actual_basic_salary;
        $totals['actual_hra'] += $actual_HRA;
        $totals['actual_con'] += $actual_CON;
        $totals['gross_total'] += $payslip->gross_salary;
        $totals['PT'] += $PT;
        $totals['PF(Employee)'] += $PF1;
        $totals['ESIC'] = 0;
        $totals['LWF'] +=$total_LWF_ded;
        $totals['OTHER DED'] +=$total_ded_other_payment;
        $totals['IT']=0;
        $totals['PF(Employer)'] += $PF2;
        $totals['Insurance'] += $Insurance;
        $totals['Gratuity'] += $Gratuity;
        $totals['total_ded'] += $total_ded;
        $totals['other_allowance'] +=$total_allow_other_payment;
        $totals['Reimbursement'] +=$total_Reimbursement;
        $totals['net_salary'] += $payslip->net_payble;
            $i++;
        }
        $result[] = array_merge(['sr_no' => 'Total'], $totals);
        return collect($result);
    }

    public function headings(): array
    {

        return [
            "SR NO",
            "EMPLOYEE Name",
            "EMP ID",
            "DATE of BIRTH",
            "DATE of JOINING",
            "SCHEDULED DAYS WITH WEEKLY OFF",
            "ACTUAL P DAYS+ W OFF",
            "FIXED BASIC",
            "FIXED HRA",
            "FIXED CON",
            "Total",
            "ACTUAL BASIC",
            "ACTUAL HRA",
            "ACTUAL ALLOWANCE",
            "GROSS TOTAL",
            "PT",
            "PF(Employee)",
            "ESIC",
            "LWF",
            "OTHER DED",
            "IT",
            "PF(Employer)",
            "Insurance",
            "Gratuity",
            "Total DED",
            "Other Allowance",
            "Reimbursement",
            "Net Salary",

            "Year Total Leave",
            "Year Taken Leave",
            "Month Taken Leave",
            "Remain Leave",
          

        ];
    }

   
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $sheet = $event->sheet;
                $delegate = $sheet->getDelegate();
    
                // Set cell values
                $delegate->setCellValue('A1', 'GetMy Solutions Pvt. Ltd.');
                $delegate->setCellValue('A2', 'SALARY STATEMENT FOR THE MONTH OF '. Carbon::createFromFormat('n', $this->data->filter_month)->format('F').' '.$this->data->filter_year.'');
                $delegate->setCellValue('A3', 'FORM (II) MAHARASHTRA WAGES RULES, 1963 RULE 27 (I)');
                $delegate->setCellValue('A4', '');
                $delegate->setCellValue('H5', 'FIXED SALARY');
                $delegate->setCellValue('L5', 'ACTUAL SALARY');
                $delegate->setCellValue('P5', 'DEDUCTIONS');
                $delegate->setCellValue('U5', 'NON STATUTORY DEDUCTIONS');
                
                // Merge cells for the title and additional lines
                $delegate->mergeCells('A1:Z1');
                $delegate->mergeCells('A2:Z2');
                $delegate->mergeCells('A3:Z3');
                $delegate->mergeCells('A4:Z4');
                $delegate->mergeCells('H5:K5');
                $delegate->mergeCells('L5:N5');
                $delegate->mergeCells('P5:T5');
                $delegate->mergeCells('U5:W5');
                // Style the title
                $styleTitle = [
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'name' => 'Arial'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $delegate->getStyle('A1:Z1')->applyFromArray($styleTitle);
    
                // Style the additional lines
                $styleLines = [
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'name' => 'Arial'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $delegate->getStyle('A2:Z2')->applyFromArray($styleLines);
                $delegate->getStyle('A3:Z3')->applyFromArray($styleLines);
                $delegate->getStyle('H5:K5')->applyFromArray($styleLines);
                $delegate->getStyle('L5:N5')->applyFromArray($styleLines);
                $delegate->getStyle('P5:T5')->applyFromArray($styleLines);
                $delegate->getStyle('U5:W5')->applyFromArray($styleLines);
                // Apply borders to the entire sheet
                $highestRow = $delegate->getHighestRow();
                $highestColumn = $delegate->getHighestColumn();
                $fullRange = 'A1:' . $highestColumn . $highestRow;
    
                $styleAllCells = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $delegate->getStyle($fullRange)->applyFromArray($styleAllCells);
            },
               AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
    
                // Add headings starting from the fourth row
                $sheet->getDelegate()->fromArray($this->headings(), null, 'A6');
    
                // Apply bold styling and borders to the headings
                $headingRange = 'A6:' . $sheet->getDelegate()->getHighestColumn() . '6';
                $styleHeadings = [
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'name' => 'Arial'
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $sheet->getDelegate()->getStyle($headingRange)->applyFromArray($styleHeadings);
    
                // Apply borders to the table body
                $startRow = 4; // Data starts from row 4
                $endRow = $sheet->getDelegate()->getHighestRow();
                $endColumn = $sheet->getDelegate()->getHighestColumn();
                $tableRange = 'A' . $startRow . ':' . $endColumn . $endRow;
    
                $styleTableBody = [
                   
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ];
                $sheet->getDelegate()->getStyle($tableRange)->applyFromArray($styleTableBody);

                $totalRowNumber = $sheet->getDelegate()->getHighestRow(); // Total row is the last row
                $totalRowRange = 'A' . $totalRowNumber . ':' . $endColumn . $totalRowNumber;

            $styleTotalRow = [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                    'name' => 'Arial'
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getDelegate()->getStyle($totalRowRange)->applyFromArray($styleTotalRow);
   
            }
        ];
    }
}
