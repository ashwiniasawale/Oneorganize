
@extends('layouts.contractheader')
@section('page-title')
    {{ __('Experience Letter ') }}
@endsection


   
@section('content')
{{-- <div class="row" >

    <div class="col-lg-10">
        <div class="container">
            <div>
                <div class="card mt-5" id="printTable" style="margin:10mm">
                
                    <div class="card-body" id="boxes">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 ">
                                    {{-- <img  src="{{$img}}" style="max-width: 150px;"/> 
                                </div>
                                
                                <p data-v-f2a183a6="" >
                                {{-- @dd($Offerletter)
                                
                                    <div >{!!$joiningletter->content!!}</div>
                                
                                    {{-- <br>
                                    <div>{!!$contract->contract_description!!}</div> 
                                </p>
                        

                        </div>
                 </div>
            </div>
        </div>
    </div>

    
</div> --}}

@section('content')
<div id="boxes">
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
        <div style="padding: 20px;">
            <p style="text-align: right; font-weight: bold;">Date:  {{date('d/m/Y')}}</p>
            <div style="margin-bottom: 20px; text-align: center;">
                <h2 style="margin: 85px 0 10px 0;">Experience Letter</h2>
            </div>
            <div style="margin-bottom: 20px; ">

            <p style="text-align: right;">Date: 10/07/2022</p>
                <h3 style="text-align: center;">Experience Letter</h3>
                <p>TO WHOM IT MAY CONCERN</p>
                <p>Dear Mr. Ramesh Gavali,</p>
                <p>This has reference to your letter of resignation dated Jun 5th, 2022, wherein you have requested to be relieved from the services of the company on July 04th, 2022.</p>
                <p>We would like to inform you that your resignation is hereby accepted and you are being relieved from the services of the company after serving one month notice period, with effect from closing office hours of July 04th, 2022.</p>
                <p>We also certify that your full and final settlement of account has been cleared with the organization.</p>
                <p>Your contributions to the organization and its success will always be appreciated.</p>
                <p>We at company wish you all the best in your future endeavors.</p>
                <p>Yours Sincerely,</p>
                <p style="margin-top: 40px;">Jaywant Mahajan<br>(Director)</p>
                <img style="width: 30%;" src="signature2.png" alt="">
                <p style="text-align: center;"><a href="http://www.gms.design" style="color: #000; text-decoration: none;">www.gms.design</a></p>
                <div
                style="padding: 10px; text-align: start; margin-top: 20px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
                <span style="font-size: 12px;">
                    +91 80 870 87000 <br>
                    contact@getmysolution.com <br>
                    <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
                </span>

                <img src="./gms-logo-footer.png" alt="gms-logo" style="width: 10%;">
                

            </div>
            <div style="margin-bottom: 20px;">
                
            </div>

            <div style="margin-bottom: 20px;">
                
            </div>

            <div style="text-align: center; margin-bottom: 20px;">
                <p style="margin: 5px 0;">mailto:contact@getmysolution.com</p>
                <p style="margin: 5px 0;">www.gms.design</p>
            </div>


        </div>
        <div
            style="padding: 10px; text-align: start; margin-top: 20px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
            <span style="font-size: 12px;">
                +91 80 870 87000 <br>
                contact@getmysolution.com <br>
                <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
            </span>

            <img src="{{ asset('assets/images/logo/gms-logo-footer.png') }}" alt="gms-logo" style="width: 10%;">
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
        filename: '{{ $employees->name }}.pdf',
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