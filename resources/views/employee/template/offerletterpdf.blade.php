@extends('layouts.contractheader')
@section('page-title')
    {{ __('Joining Letter') }}
@endsection



@section('content')
    <div id="boxes">
        {{-- page one  --}}
        <div
            style="width: 800px; max-width: 100%; padding: 10px;padding-left:40px;padding-right:40px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); border-radius: 5px;">
            <div id="header"
                style="padding: 10px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
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
            <div id="main-content" style="padding: 20px;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <p></p>
                    <p>Date: {{date('d/m/Y')}}</p>
                </div>
                <div style="margin-bottom: 20px;">
                    <p style="margin: 5px 0;">To,</p>
                    <p style="margin: 5px 0;">{{$employees->title}} {{$obj['employee_name']}},</p>
                    <p style="margin: 5px 0;">Pune.</p>
                   
                </div>
                <div style="margin-bottom: 20px; text-align: center;">
                <p style="text-align: center; font-weight: bold; text-decoration: underline;">CONGRATULATIONS</p>
                
                <p style="text-align: center; font-weight: bold; text-decoration: underline;">Subject: Offer Letter</p>
                   
                </div>
                <div style="margin-bottom: 20px;">
                    <p style="margin: 5px 0;">Dear {{$obj['employee_name']}},</p>
                    <p style="margin: 5px 0;">With reference to your application and subsequent interview with us, we are
                        pleased to offer you the following position.</p>
                    <div style="margin-bottom: 20px;">
                        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                            <thead>
                                <tr>
                                    <th
                                        style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                                        Position</th>
                                    <th
                                        style="padding: 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                                       {{$obj['designation']}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Location</td>
                                    <td style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        GetMy Solutions Pvt Ltd<br>
                                        407, City Center, Behind Persistent,<br> Hinjewadi Ph 1, Pune-411057.</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold; background-color: #f2f2f2;">
                                        Probation</td>
                                    <td
                                        style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold; background-color: #f2f2f2;">
                                        3 Months</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Salary</td>
                                    <td style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold;">
                                        Rs.{{$employees->salary}}/- per year(All Inclusive)<br>
                                        see the Annexure A</td>
                                </tr>
                                <tr>
                                    <td
                                        style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold; background-color: #f2f2f2;">
                                        Joining Date:
                                    </td>
                                    <td
                                        style="padding: 10px; text-align: left; border: 1px solid #ddd; font-weight: bold; background-color: #f2f2f2;">
                                        On or Before {{ date('jS F, Y', strtotime($employees->joining_date))}}.
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <p style="margin: 5px 0;">We welcome you and look forward to a long and successful association.</p>
                    <p> Yours
                        sincerely</p>
                    <p>For GetMy Solutions Pvt. Ltd. </p>

                    <span><strong>Signature </strong> </span>
                    <div>
                        <img src="{{ asset('assets/images/logo/signature.png') }}" alt="gms-Signature" style="width: 20%;">

                    </div>
                    <p> Mr. Jaywant Mahajan <br> <span style="margin-left: 3rem;">Director</span> </p>

                </div>
            </div>
            <!-- <div id="footer"
                style="padding: 10px; text-align: start; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
                <span class="pageNumber" style="font-size: 12px;">
                    +91 80 870 87000 <br>
                    contact@getmysolution.com <br>
                    <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
                </span>
            </div> -->



            {{-- page two start --}}

            <!-- <div id="header"
                style="margin-top:65px; padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
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
            </div> -->
           
            <div id="main-content" style="padding: 0px 20px;">
                <div style="margin-bottom: 10px;">
                    <h5 style="margin-top:130px; text-align: center;">Annexure A</h5>
                    <p>The break up of your monthly/yearly salary can be found below:</p>
                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr>
                                <th style="padding: 2px 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                                    Earnings</th>
                                <th style="padding: 2px 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                                    Yearly</th>
                                <th style="padding: 2px 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
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
                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr>
                                <th style="padding: 2px 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                                    Deductions</th>
                                <th style="padding: 2px 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
                                    Yearly</th>
                                <th style="padding: 2px 10px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">
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

                <div style="margin-bottom: 20px;">
                    <h5 style="text-align: center;">Annexure B</h5>
                    <p style="margin: 0 0 10px 0; text-align: center;">Sub: Joining Formalities</p>
                    <p style="margin: 5px 0;">As part of ourjoining formalities, you are requested to submit the
                        following
                        documents preferably before your date of joining:</p>
                        <?php if($employees['employee_type']=='Fresher') 
                        { ?>
                    <ol style="margin: 10px 0 0 20px; padding:0 0 0 20px">
                        <li style="margin: 5px 0;">Duly filled in enclosed Employment Application.</li>
                        <li style="margin: 5px 0;">Signed copy of your letter of offer.</li>
                        <li style="margin: 5px 0;">Copies of all qualification certificates and mark sheets (semester
                            wise/Consolidated) from S.S.C onwards. Highest Qualification Provisional Certificate and
                            Degree
                            Certificate front side and back side.</li>
                        <li style="margin: 5px 0;">Aadhar Card, PAN Card.</li>
                      
                        <li style="margin: 5px 0;">Copy of your resume.</li>
                        <li style="margin: 5px 0;">Passport size photographs Scan.</li>
                    </ol>
                    <?php }else{ ?>
                    <ol style="margin: 10px 0 0 20px; padding:0 0 0 20px">
                        <li style="margin: 5px 0;">Duly filled in enclosed Employment Application.</li>
                        <li style="margin: 5px 0;">Signed copy of your letter of offer.</li>
                        <li style="margin: 5px 0;">Copies of all qualification certificates and mark sheets (semester
                            wise/Consolidated) from S.S.C onwards. Highest Qualification Provisional Certificate and
                            Degree
                            Certificate front side and back side.</li>
                        <li style="margin: 5px 0;">Aadhar Card, PAN Card.</li>
                       
                        <li style="margin: 5px 0;">Copy of your resume.</li>
                        <li style="margin: 5px 0;">Passport size photographs Scan.</li>
                        <li style="margin: 5px 0;">Copy of Last two month's pay slip.</li>
                        <li style="margin: 5px 0;">Copy of Relieving letter from your last employer.</li>
                        <li style="margin: 5px 0;">Copy of offer Letter from your last employer.</li>
                        <li style="margin: 5px 0;">Copies of experience letters/ Service Certificates from current and previous Employers.</li>
                    </ol>
                    <?php } ?>
                    <div
                        style="margin-top: 20px; padding: 10px; background-color: #fffbcc; border: 1px solid #ffeb3b; border-radius: 5px;">
                        <p style="margin: 5px 0;"><strong>Note:</strong></p>
                        <ul style="margin: 10px 0 0 20px; padding: 0;">
                            <li style="margin: 5px 0;">Standard Office time is from 8 am to 5 pm. Sundays Off. 1st and
                                3rd
                                Saturdays off.</li>
                            <li style="margin: 5px 0;">You have to complete your joining formalities before date of
                                joining
                                as soon as possible.</li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- end main div --}}
            <!-- <div id="footer"
                style="padding: 10px; text-align: start; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
                <span class="pageNumber" style="font-size: 12px;">
                    +91 80 870 87000 <br>
                    contact@getmysolution.com <br>
                    <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
                </span>
            </div> -->

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
                    filename: 'GMS-Offer Letter-{{ $employees->employee_name }}.pdf',
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
