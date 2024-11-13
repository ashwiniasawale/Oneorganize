
@extends('layouts.contractheader')
@section('page-title')
    {{ __('Releiving Letter ') }}
@endsection


   
@section('content')


@section('content')
<div id="boxes">
<div
        style="width: 800px; max-width: 100%; margin: 0px auto; padding: 30px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); border-radius: 5px;"> 
        <div
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
        <div style="padding: 20px;">
            <p style="text-align: right; font-weight: bold;">Date:  {{date('d/m/Y')}}</p>
            <div style="margin-bottom: 20px; ">
                <h3 style="text-align: center;">Releiving Letter</h3>
                <p>TO WHOM IT MAY CONCERN</p>
                <p>Dear {{$obj['employee_name']}},</p>
                <p>This has reference to your letter of resignation dated <strong>{{date('jS F, Y', strtotime($obj['notice_date']))}}</strong>, where in you have requested to be relieved from the services of the company on <strong>{{date('jS F, Y', strtotime($obj['resignation_date']))}} </strong>.</p>
                <p>We would like to inform you that your resignation is hereby accepted and you are being relieved from the services of the company after serving one month notice period, with effect from closing office hours of <strong>{{date('jS F, Y', strtotime($obj['resignation_date']))}}</strong>.</p>
                <p>We also certify that your full and final settlement of account has been cleared with the organization.</p>
                <p>Your contributions to the organization and its success will always be appreciated.</p>
                <p>We at company wish you all the best in your future endeavors.</p>
                <p style="text-align: right; margin-right: 80px;">Best Regards,</p>
                <div style="margin-top: 10px; display: flex; justify-content: right; flex-direction: column; align-items: flex-end; margin: 5px 20px;">
                    <img src="{{ asset('assets/images/logo/signature.png') }}" alt="gms-Signature" style="width: 20%; margin: 10px 0px;">
                    <p style="text-align: right; margin: 0;">Mr. Jaywant Mahajan<br><span style="margin-right: 40px; ">Director</span></p>
                </div>
            <div style="margin-bottom: 20px;">
                
            </div>

            <div style="margin-bottom: 20px;">
                
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                
            </div>


        </div>
        <div
            style=" text-align: start; margin-top: 165px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
            <span style="font-size: 12px;">
                +91 80 870 87000 <br>
                contact@getmysolution.com <br>
                <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
            </span>
        </div>
</div>




@endsection
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
    function closeScript() {
            setTimeout(function () {
                window.open(window.location, '_self').close();
            }, 1000);
        }

        
        $(window).on('load', function() {
            var element = document.getElementById('boxes');
            var opt = {
                margin: [0, 0, 0, 0],
                filename: 'GMS-Relieving Letter-{{ $employees->name }}.pdf',
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
                    format: 'letter',
                    orientation: 'portrait'
                },
                pdfCallback: function(doc) {
            // Access the footer element
            var footer = document.getElementById('footer');
            var pageNumberSpan = footer.querySelector('.pageNumber');

            // Get actual page count
            var totalPages = doc.internal.getNumberOfPages();

            // Update footer content before saving
            pageNumberSpan.textContent = totalPages;
        }
        };

        html2pdf().set(opt).from(element).save().then(closeScript);
    });

        
    </script>
    
@endpush