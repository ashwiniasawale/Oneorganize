@extends('layouts.contractheader')
@section('page-title')
    {{ __('Joining Letter') }}
@endsection

 

@section('content')
    <div id="boxes">
        {{-- page one  --}}
        <div
        style="width: 800px; max-width: 100%; margin: 0px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); border-radius: 5px;">
        <div
            style="padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
            <img src="{{ asset('assets/images/logo/gms-logo.jpg') }}" alt="gms-logo" style="width: 30%;">
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
                <h2 style="margin: 45px 0 10px 0; text-align: left;">Payslip for the month of June 2024</h2>
            </div>
        </div>

        <div>
            <table  style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Employee ID:</span>123456</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Name:</span> {{$obj['employee_name']}}</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Salary Slip ID: </span>1125</td>
                </tr>
                <tr>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Department:</span> Design Department</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Designation:</span> Design Engineer</td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Joining Date:</span> 25-04-2024</td>
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
                    <td style="padding: 12px; text-align: left;">13790 INR</td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">HRA</td>
                    <td style="padding: 12px; text-align: left;">4137 INR</td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">Fixed Allowance</td>
                    <td style="padding: 12px; text-align: left;">9653 INR</td>
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
                    <td style="padding: 12px; text-align: left;"></td>
                    <td style="padding: 12px; text-align: left;"></td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">Gross Earnings</td>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">27580 INR</td>
                </tr>
            </table>
            
            <!-- Deductions Table -->
            <table style="border-collapse: collapse; width: 45%; margin-left: 10px;">
                <tr>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">DEDUCTIONS</th>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">AMOUNT</th>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">PF Employee</td>
                    <td style="padding: 12px; text-align: left;">1800 INR</td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">PF Employer</td>
                    <td style="padding: 12px; text-align: left;">1800 INR</td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">Insurance</td>
                    <td style="padding: 12px; text-align: left;">250 INR</td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">Gratuity</td>
                    <td style="padding: 12px; text-align: left;">663 INR</td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">Prof. Tax</td>
                    <td style="padding: 12px; text-align: left;">200 INR</td>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">Total Deductions</td>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">4713 INR</td>
                </tr>
            </table>
        </div>
    
        <div style="display: flex; justify-content: space-between; margin-top: 25px;">
            <!-- Reimbursements Table -->
            <table style="border-collapse: collapse; width: 100%; margin-right: 10px;">
                <tr>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">Reimbursements</th>
                    <th style="padding: 12px; text-align: right; background-color: lightgray;">AMOUNT</th>
                </tr>
                <tr>
                    <td style="padding: 12px; text-align: left;">Expense Claims</td>
                    <td style="padding: 12px; text-align: right;">0 INR</td>
                </tr>
            </table>
        </div>
    
        <div>
            <h3 style="font-weight: bold; text-align: center;">NET SALARY: <span style="font-weight:100;">22867.00 INR</span></h3>
            <p style="font-weight: bold; text-align: center; color: rgb(78, 78, 78);">Net Salary = (Gross Earnings - Total Deductions + Reimbursements)</p>
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
    @push('script-page')
        <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
        <script>
            function closeScript() {
                setTimeout(function() {
                    window.open(window.location, '_self').close();
                }, 1000);
            }

            $(window).on('load', function() {
                var element = document.getElementById('boxes');
                var opt = {
                    margin: [0, 0, 0, 0], // Adjust margins as needed (in inches)
                    filename: 'GMS-Salary Slip-{{ $employees->name }}.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 1
                    },
                    html2canvas: {
                        scale: 1.5,
                        dpi: 72,
                        letterRendering: true
                    },
                    jsPDF: {
                        unit: 'in',
                        format: 'A4', // Set page size to A4
                        orientation: 'portrait'
                    }
                };

                html2pdf().set(opt).from(element).save().then(closeScript);
            });
        </script>
    @endpush
