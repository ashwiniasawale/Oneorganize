@extends('layouts.contractheader')
@section('page-title')
    {{ __('Appraisal Letter') }}
@endsection



@section('content')
@section('content')
    <div id="boxes">
        {{-- page one  --}}
        <div
            style="width: 800px; max-width: 100%; margin: 0px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); border-radius: 5px;">

            <div id="header"
                style="padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
                <img src="{{ asset('assets/images/logo/gms-logo.jpeg') }}" alt="gms-logo" style="width: 30%;">
                <div>
                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                    <span style="font-size: 6px; line-height: 1.2;">
                <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
                <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
                <p style="margin: 0;">+91 80 870 87000</p>
                <p style="margin: 0;">contact@getmysolution.com</p>
                <p style="margin: 0;">www.gms.design</p>
                    </span>
                </div>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                    <p></p>
                    <p><strong> Date: {{date('d/m/Y')}}</strong></p>
                </div>
                <div style="margin-bottom: 20px;">
                    <p style="margin: 5px 0;"><strong>To,</strong></p>
                    <p style="margin: 5px 0;">{{$employees->name}},</p>
                    <p style="margin: 5px 0;"><strong>{{$obj['designation']}}</strong></p>
                   
                </div>
           
            <div style="margin: 5px 20px;">
                <p style="font-weight: bold; text-align:center;">Subject: Appraisal Letter </p>
                <p>Dear {{$employees->name}},</p>
                <p>Get My Solutions has and continues to move forward because of your hard work and contributions. Get My Solutions,
                as always, stays committed to its people first approach and puts you and your contributions at the forefront.</p>
                <p>In continuation to that thought and philosophy, we are taking this opportunity to congratulate and recognise you for
                your contributions and thank you for all your efforts.</p>
                <p>In recognition of your performance and contributions to Get My Solutions, we are delighted to promote you to
                <strong>{{$obj['designation']}}</strong> and revise your Cost to Company to <strong>INR {{$appraisal->appraisal_salary}}</strong>, effective from <strong>{{$appraisal->appraisal_date}}</strong> The breakdown of your CTC is mentioned in Annexure A.</p>
                <p>We wish you tremendous success in the coming years and look forward to your long-term association and contributions
                to Get My Solutions.
                </p>
            </div>
    
            <p style="text-align: right; margin-right: 80px;">Best Regards,</p>
            <div style="margin-top: 10px; display: flex; justify-content: right; flex-direction: column; align-items: flex-end; margin: 5px 20px;">
                <img src="{{ asset('assets/images/logo/signature.png') }}" alt="gms-Signature" style="width: 20%; margin: 10px 0px;">
                <p style="text-align: right; margin: 0;">Mr. Jaywant Mahajan<br><span style="margin-right: 40px; ">Director</span></p>
            </div>

            <div id="footer"
                style="padding: 10px; text-align: start; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black; margin-top: 55px">
                <span class="pageNumber" style="font-size: 12px;">
                    +91 80 870 87000 <br>
                    contact@getmysolution.com <br>
                    <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
                </span>
            </div>

            <div id="header"
                style="margin-top: 165px; padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
                <img src="{{ asset('assets/images/logo/gms-logo.jpeg') }}" alt="gms-logo" style="width: 30%;">
                <div>

                <h1 style="margin: 0; font-size: 16px;font-weight:700;">GetMy Solutions Pvt. Ltd.</h1>
                    <span style="font-size: 6px; line-height: 1.2;">
                <p style="margin: 0;"><strong>Head Office:</strong> 406/7, City Centre,</p>
                <p style="margin: 0;"> Hinjewadi Ph 1, Pune-411057. </p>
                <p style="margin: 0;">+91 80 870 87000</p>
                <p style="margin: 0;">contact@getmysolution.com</p>
                <p style="margin: 0;">www.gms.design</p>
                    </span>       
                </div>
            </div>

            <div id="main-content" style="padding: 0px 20px;"> 
                <div style="margin-bottom: 10px;">
                    <h5 style="margin-top:10px; text-align: center;">Annexure A</h5>
                    <p>This is your expected monthly salary structure</p>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Earnings</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Yearly</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Monthly</th>
                    </tr>
                </thead>
                <tbody>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Basic</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['basicSal']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['basicSal']/12)}}.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">HRA 30%</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['HRA']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['HRA']/12)}}.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Fixed Allow.</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['fixedAllow']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['fixedAllow']/12)}}.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Total
                                        CTC</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹{{$employees->salary}}.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹{{round($structure['totalCTC']/12)}}.00</strong>
                                </td>
                            </tr>
                        </tbody>
            </table>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Deductions</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Yearly</th>
                        <th
                            style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                            Monthly</th>
                    </tr>
                </thead>
                <tbody>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PF Employee
                                    contribution</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['PF1']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['PF1']/12)}}.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PF Employer
                                    contribution</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['PF2']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['PF2']/12)}}.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PT</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['PT']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['PT'] / 12)}}.00 
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Insurance</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['insurance']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['insurance']/12)}}.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Gratuity</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{$structure['gratuity']}}.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹{{round($structure['gratuity']/12)}}.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Total
                                        Deductions</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹{{round($structure['totalDeduction'])}}.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹{{round($structure['monthlyTotalDed'])}}.00</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Net In
                                        Hand</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹{{round($structure['netInHand'])}}.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹{{round($structure['monthlyInhand'])}}.00</strong>
                                </td>
                            </tr>
                        </tbody>
            </table>


                </div>
            </div>
                

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
                    filename: 'GMS-Appraisal Letter-{{ $employees->name }}.pdf',
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
                        format: 'a4', // Set page size to A4
                        orientation: 'portrait'
                    }
                };

                html2pdf().set(opt).from(element).save().then(closeScript);
            });
        </script>
    @endpush
