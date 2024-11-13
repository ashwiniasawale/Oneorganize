
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Increament Letter')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
<div id="boxes" style="width: 800px; max-width: 100%; margin: 0px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); border-radius: 5px;">
       
        <div style="padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 30%;">
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
                    <p><strong> Date: <?php echo e(date('d/m/Y')); ?></strong></p>
                </div>
                <div style="margin-bottom: 20px;">
                    <p style="margin: 5px 0;"><strong>To,</strong></p>
                    <p style="margin: 5px 0;"><?php echo e($employees->name); ?>,</p>
                    <p style="margin: 5px 0;"><strong><?php echo e($obj['designation']); ?></strong></p>
                   
                </div>
        <div style="margin: 5px 25px 20px 25px;">
            <p style="font-weight: bold; text-align:center;">Subject: Salary Increment</p>
            <p>Dear <?php echo e($obj['employee_name']); ?>,</p>
            <p>We hope this letter finds you in good health and high spirits. We are pleased to inform you that in recognition of your dedication, hard work, and exceptional performance over the past year, we have decided to offer you a salary increment effective from <?php echo e($increament->appraisal_date); ?>.</p>
            <p>Your revised salary details are as follows:</p>
            <ul>
               
                <li>Revised Salary: <strong><?php echo e($increament->appraisal_salary); ?> INR</strong> per annum</li>
            </ul>
            <p>This increment reflects our appreciation of your contributions and the value you bring to our organization. We have been particularly impressed with your achievements, projects and we are confident that you will continue to excel in your role.</p>
            <p>Please find your revised salary structure attached with this letter for your reference.</p>
            <p>We look forward to your continued dedication and exemplary performance. Should you have any questions or need further clarification regarding this increment, please feel free to reach out to the HR department.</p>
            <p>Thank you for your hard work and commitment to GetMy Solutions Pvt. Ltd.</p>
        </div>
        <p style="text-align: right; margin-right: 80px;">Best Regards,</p>
            <div style="margin-top: 10px; display: flex; justify-content: right; flex-direction: column; align-items: flex-end; margin: 5px 20px;">
                <img src="<?php echo e(asset('assets/images/logo/signature.png')); ?>" alt="gms-Signature" style="width: 20%; margin: 10px 0px;">
                <p style="text-align: right; margin: 0;">Mr. Jaywant Mahajan<br><span style="margin-right: 40px; ">Director</span></p>
            </div>
        <div style="padding: 10px; text-align: start; margin-top: 15px; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid black;">
            <span style="font-size: 12px;">
                +91 80 870 87000 <br>
                contact@getmysolution.com <br>
                <a href="http://www.gms.design/" style="color: black; text-decoration: none;">www.gms.design</a>
            </span>
        </div>

        <div id="header"
                style="margin-top: 165px; padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
                <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 30%;">
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
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['basicSal']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['basicSal']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">HRA 30%</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['HRA']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['HRA']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Fixed Allow.</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['fixedAllow']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['fixedAllow']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Total
                                        CTC</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e($employees->salary); ?>.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['totalCTC']/12)); ?>.00</strong>
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
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['PF1']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['PF1']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PF Employer
                                    contribution</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['PF2']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['PF2']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">PT</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['PT']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['PT'] / 12)); ?>.00 
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Insurance</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['insurance']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['insurance']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">Gratuity</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e($structure['gratuity']); ?>.00</td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">₹<?php echo e(round($structure['gratuity']/12)); ?>.00</td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Total
                                        Deductions</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['totalDeduction'])); ?>.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['monthlyTotalDed'])); ?>.00</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;"><strong>Net In
                                        Hand</strong></td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['netInHand'])); ?>.00</strong>
                                </td>
                                <td style="padding: 2px 10px; text-align: left; border: 1px solid #ddd;">
                                    <strong>₹<?php echo e(round($structure['monthlyInhand'])); ?>.00</strong>
                                </td>
                            </tr>
                        </tbody>
            </table>


                </div>
            </div>
    </div> 
    <?php $__env->stopSection(); ?>
    <?php $__env->startPush('script-page'); ?>
        <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
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
                    filename: 'GMS-Increment Letter-<?php echo e($employees->name); ?>.pdf',
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
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.contractheader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/employee/template/IncrementLetterpdf.blade.php ENDPATH**/ ?>