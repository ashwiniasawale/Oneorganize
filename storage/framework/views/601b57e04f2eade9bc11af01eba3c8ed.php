

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Joining Letter')); ?>

<?php $__env->stopSection(); ?>


   
<?php $__env->startSection('content'); ?>


<?php $__env->startSection('content'); ?>
<div id="boxes">
<div
        style="width: 800px; max-width: 100%; margin: 0px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); border-radius: 5px;">
        <div
            style="padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpg')); ?>" alt="gms-logo" style="width: 30%;">
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
            <p style="text-align: right; font-weight: bold;">Date:  <?php echo e(date('d/m/Y')); ?></p>
            <div style="margin-bottom: 20px; text-align: center;">
                <h2 style="margin: 85px 0 10px 0;">CERTIFICATE OF EMPLOYMENT</h2>
            </div>
            <div style="margin-bottom: 20px; ">

                <p style="margin: 5px 15px;  text-indent: 30px;">This is to certify that Mr/Mrs/Ms <?php echo e($obj['employee_name']); ?> has been employed in our company as a <?php echo e($obj['designation']); ?> from  <?php echo e(date('jS F, Y', strtotime($employees->company_doj))); ?>.</p>
                <p style="margin: 5px 15px;  text-indent: 30px;">This certification is being issued upon his request for Rent Agreement purposes it may.</p>
    

                <p style="text-align: right; margin-right: 80px; margin-top: 2rem;">Best Regards,</p>
                <div style="margin-top: 10px; display: flex; justify-content: right; flex-direction: column; align-items: flex-end; margin: 5px 20px;">
                    <img src="<?php echo e(asset('assets/images/logo/signature.png')); ?>" alt="gms-Signature" style="width: 20%; margin: 10px 0px;">
                    <p style="text-align: right; margin: 0;">Mr. Jaywant Mahajan<br><span style="margin-right: 40px; ">Director</span></p>
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                
            </div>

            <div style="margin-bottom: 20px;">
                
            </div>

 

        </div>
        <div
            style="padding: 10px; text-align: start; margin-top: 210px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
            <span style="font-size: 12px;">
                +91 80 870 87000 <br>
                contact@getmysolution.com <br>
                <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
            </span>

        </div>
</div>




<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
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
        filename: '<?php echo e($employees->name); ?> Working letter.pdf',
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
    
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.contractheader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/employee/template/WorkingLetterpdf.blade.php ENDPATH**/ ?>