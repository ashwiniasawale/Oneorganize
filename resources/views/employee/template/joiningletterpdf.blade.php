
@extends('layouts.contractheader')
@section('page-title')
    {{ __('Joining Letter') }}
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


<div id="boxes">
    <div id="header"
        style="width: 800px; max-width: 100%; margin: 50px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); border-radius: 5px;">
        <div
            style="padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
            <img src="{{ asset('assets/images/logo/gms-logo.png') }}" alt="gms-logo" style="width: 30%;">
            <div>
                <h1 style="margin: 0; font-size: 18px;">GetMy Solutions Pvt. Ltd.</h1>
                <span style="font-size: 12px;">
                    Head Office: Laxmi Niwas, Ashok Nagar, <br>
                    Tathawade, Pune-411057 <br>
                    +91 80 870 87000 <br>
                    contact@getmysolution.com
                </span>
            </div>

        </div>
        <div style="padding: 20px;">
            <div>{!!$joiningletter->content!!}</div>
        </div>
        <!-- <div style="text-align: right; margin-top: 30px;">
            <p style="margin: 0;">Sincerely,</p>
            <p style="margin: 0;">[Company Representative Name]</p>
            <p style="margin: 0;">[Company Representative Title]</p>
        </div> -->
        <div id="footer"
            style="padding: 10px; text-align: start; margin-top: 20px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
            <span class="pageNumber" style="font-size: 12px;">
                +91 80 870 87000 <br>
                contact@getmysolution.com <br>
                <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
            </span>

            <img src="{{ asset('assets/images/logo/gms-logo-footer.png') }}" alt="gms-logo" style="width: 10%;">
        </div>
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

        // $(window).on('load', function () {
        //     var element = document.getElementById('boxes');
        //     var opt = {
        //         margin: [0, 0, 0, 0], // top, right, bottom, left margins in inches
        //         filename: '{{$employees->name}}.pdf',
        //         image: {type: 'jpeg', quality: 1},
        //         html2canvas: {scale: 1.5, dpi: 72, letterRendering: true},
        //         jsPDF: {unit: 'in', format: 'letter', orientation: 'portrait'}
        //     };

        //     html2pdf().set(opt).from(element).save().then(closeScript);
        // });
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