@extends('layouts.contractheader')
@section('page-title')
    {{ __('Joining Letter') }}
@endsection




@section('content')
<div class="text-end">
            <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"><span class="ti ti-download"></span></a>
            <!-- <a title="Mail Send" href="{{route('payslip.send',[$employee->id,$payslip->salary_month])}}" class="btn btn-sm btn-warning"><span class="ti ti-send"></span></a>
        -->
        </div>
    <div id="printableArea">
        {{-- page one  --}}
        <div
        style="width: 800px; max-width: 100%; margin: 0px auto; padding: 20px; background-color: #fff;  border-radius: 5px;">
        <div
            style="padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
            <img src="{{ asset('assets/images/logo/gms-logo.jpeg') }}" alt="gms-logo" style="width: 30%;">
            <div>
                <h1 style="margin: 0; font-size: 18px;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 12px;">
                        407, City Center, Behind Persistent,<br>
                        Hinjewadi Ph 1, Pune-411057.<br>
                        +91 80 870 87000 <br>
                        contact@getmysolution.com
                </span>
            </div>

        </div>
        

        <div>
           <p style="text-align: right; font-weight: bold;">Date:  {{date('d/m/Y')}}</p>
            <div style="margin-bottom: 20px; text-align: center;">
                
            <?php 
             // Create a DateTime object from the given string
             $date = DateTime::createFromFormat('Y-m',$payslip->salary_month);
    
   
            ?>
                <h2 style="margin: 45px 0 10px 0; text-align: left;">Payslip for the month of {{ $date->format('F Y') }}</h2>
            </div>
        </div>

        <div>
            <table  style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Employee ID:</span>3000{{$employee->employee_id}}</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Name:</span> {{$employee->name}}</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Designation: </span>{{$employee->designation->name}}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Total Days:</span> {{$payslip->total_days}}</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Present Days:</span> {{$payslip->present_days}}</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Joining Date:</span> {{$employee->company_doj}}</td>
                </tr>
            </table>
        </div>


        <div style="display: flex; justify-content: space-between; margin-top: 25px;">
            <!-- Earnings Table -->
            <table style="border-collapse: collapse; width: 45%; margin-right: 10px;">
                <tr>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">EARNINGS</th>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">AMOUNT</th>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">Basic Salary</td>
                    <td style="padding: 12px; text-align: left;">{{$payslip->actual_basic_salary}} INR</td>
                </tr>
                @php $allowance = json_decode($payslip->actual_allowance);
                $total_earning =0;
                @endphp
                @foreach ($allowance as $all)
                                        
                <tr>
                    <td style="padding: 12px; text-align: left;">{{$all->title}}</td>
                    <td style="padding: 12px; text-align: left;">{{$all->amount}} INR</td>
                </tr>
                @php $total_earning +=$all->amount;
                @endphp
                @endforeach
               
                <tr>
                    <td style="padding: 12px; text-align: left;"></td>
                    <td style="padding: 12px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;"></td>
                    <td style="padding: 12px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;"></td>
                    <td style="padding: 12px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">Gross Earnings</td>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">{{$payslip->gross_salary}} INR</td>
                </tr>
            </table>
            
            <!-- Deductions Table -->
            <table style="border-collapse: collapse; width: 45%; margin-left: 10px;">
                <tr>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">DEDUCTIONS</th>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">AMOUNT</th>
                </tr>

                @php $deduction = json_decode($payslip->actual_saturation_deduction);
                $total_deduction=0;
                @endphp

                @foreach ($deduction as $saturationdeduc)
                <tr>
                    <td style="padding: 12px; text-align: left;">{{$saturationdeduc->title}}</td>
                    <td style="padding: 12px; text-align: left;">{{$saturationdeduc->amount}} INR</td>
                </tr>
                @php $total_deduction +=$saturationdeduc->amount;
                @endphp
                @endforeach
               
                <tr>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">Total Deductions</td>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">{{$total_deduction}} INR</td>
                </tr>
            </table>
        </div>
    
        <div style="display: flex; justify-content: space-between; margin-top: 25px;">
            <!-- Reimbursements Table -->
            <table style="border-collapse: collapse; width: 100%; margin-right: 10px;">
                <tr>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">Other Payment</th>
                    <th style="padding: 12px; text-align: right; background-color: lightgray;">AMOUNT</th>
                </tr>
                @php $other_payment = json_decode($payslip->other_payment);
                $total_minus=0;
                $total_plus=0;
                @endphp
                 <?php if($other_payment)
                 { ?>
                @foreach ($other_payment as $op)
                @php 
                if($op->payment_option=='deduction')
                {
                    $total_minus +=$op->amount;
                }else{
                    $total_plus +=$op->amount;
                }
                @endphp
                <tr>
                    <td style="padding: 12px; text-align: left;">{{$op->title}}($op->payment_option)</td>
                    <td style="padding: 12px; text-align: right;">{{$op->amount}} INR</td>
                </tr>
                @endforeach
                <?php }else{ ?>
                    <tr>
                    <td style="padding: 12px; text-align: left;"></td>
                    <td style="padding: 12px; text-align: right;">00 INR</td>
                </tr>
                <?php } ?>               
              
            </table>
        </div>
    
        <div>
            <h3 style="font-weight: bold; text-align: center;">NET SALARY: <span style="font-weight:100;">{{ $payslip->net_payble}}.00 INR</span></h3>
            <p style="font-weight: bold; text-align: center; color: rgb(78, 78, 78);">Net Salary = (Gross Earnings - Total Deductions + Other Payment(Allowance) - Other Payment (Deduction))</p>
        </div>

        <div
            style="padding: 10px; text-align: start; margin-top: 120px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
            <span style="font-size: 12px;">
                +91 80 870 87000 <br>
                contact@getmysolution.com <br>
                <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
            </span>

        </div>
    </div>
    
    </div>  
    @endsection
    
<script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script>

    var filename = $('#filename').val()

    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: filename,
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A2'}
        };
        html2pdf().set(opt).from(element).save();
    }
</script>

