
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Joining Letter')); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>
<div class="text-end">
            <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"><span class="ti ti-download"></span></a>
            <!-- <a title="Mail Send" href="<?php echo e(route('payslip.send',[$employee->id,$payslip->salary_month])); ?>" class="btn btn-sm btn-warning"><span class="ti ti-send"></span></a>
        -->
        </div>
    <div id="printableArea">
        
        <div
        style="width: 800px; max-width: 100%; margin: 0px auto; padding: 20px; background-color: #fff;  border-radius: 5px;">
        <div
            style="padding: 20px; display: flex; align-items: start; justify-content: space-between; border-bottom: 1px solid black;">
            <img src="<?php echo e(asset('assets/images/logo/gms-logo.jpeg')); ?>" alt="gms-logo" style="width: 30%;">
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
           <p style="text-align: right; font-weight: bold;">Date:  <?php echo e(date('d/m/Y')); ?></p>
            <div style="margin-bottom: 20px; text-align: center;">
                
            <?php 
             // Create a DateTime object from the given string
             $date = DateTime::createFromFormat('Y-m',$payslip->salary_month);
    
   
            ?>
                <h2 style="margin: 45px 0 10px 0; text-align: left;">Payslip for the month of <?php echo e($date->format('F Y')); ?></h2>
            </div>
        </div>

        <div>
            <table  style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Employee ID:</span>3000<?php echo e($employee->employee_id); ?></td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Name:</span> <?php echo e($employee->name); ?></td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Designation: </span><?php echo e($employee->designation->name); ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Total Days:</span> <?php echo e($payslip->total_days); ?></td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Present Days:</span> <?php echo e($payslip->present_days); ?></td>
                    <td style="padding: 8px; text-align: left;"><span style="font-weight: bold;">Joining Date:</span> <?php echo e($employee->company_doj); ?></td>
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
                    <td style="padding: 12px; text-align: left;"><?php echo e($payslip->actual_basic_salary); ?> INR</td>
                </tr>
                <?php $allowance = json_decode($payslip->actual_allowance);
                $total_earning =0;
                ?>
                <?php $__currentLoopData = $allowance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $all): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                <tr>
                    <td style="padding: 12px; text-align: left;"><?php echo e($all->title); ?></td>
                    <td style="padding: 12px; text-align: left;"><?php echo e($all->amount); ?> INR</td>
                </tr>
                <?php $total_earning +=$all->amount;
                ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               
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
                    <td style="padding: 12px; text-align: left; font-weight: bold;"><?php echo e($payslip->gross_salary); ?> INR</td>
                </tr>
            </table>
            
            <!-- Deductions Table -->
            <table style="border-collapse: collapse; width: 45%; margin-left: 10px;">
                <tr>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">DEDUCTIONS</th>
                    <th style="padding: 12px; text-align: left; background-color: lightgray;">AMOUNT</th>
                </tr>

                <?php $deduction = json_decode($payslip->actual_saturation_deduction);
                $total_deduction=0;
                ?>

                <?php $__currentLoopData = $deduction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saturationdeduc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="padding: 12px; text-align: left;"><?php echo e($saturationdeduc->title); ?></td>
                    <td style="padding: 12px; text-align: left;"><?php echo e($saturationdeduc->amount); ?> INR</td>
                </tr>
                <?php $total_deduction +=$saturationdeduc->amount;
                ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               
                <tr>
                    <td style="padding: 12px; text-align: left; font-weight: bold;">Total Deductions</td>
                    <td style="padding: 12px; text-align: left; font-weight: bold;"><?php echo e($total_deduction); ?> INR</td>
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
                <?php $other_payment = json_decode($payslip->other_payment);
                $total_minus=0;
                $total_plus=0;
                ?>
                 <?php if($other_payment)
                 { ?>
                <?php $__currentLoopData = $other_payment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                if($op->payment_option=='deduction')
                {
                    $total_minus +=$op->amount;
                }else{
                    $total_plus +=$op->amount;
                }
                ?>
                <tr>
                    <td style="padding: 12px; text-align: left;"><?php echo e($op->title); ?>($op->payment_option)</td>
                    <td style="padding: 12px; text-align: right;"><?php echo e($op->amount); ?> INR</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php }else{ ?>
                    <tr>
                    <td style="padding: 12px; text-align: left;"></td>
                    <td style="padding: 12px; text-align: right;">00 INR</td>
                </tr>
                <?php } ?>               
              
            </table>
        </div>
    
        <div>
            <h3 style="font-weight: bold; text-align: center;">NET SALARY: <span style="font-weight:100;"><?php echo e($payslip->net_payble); ?>.00 INR</span></h3>
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
    <?php $__env->stopSection(); ?>
    
<script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
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


<?php echo $__env->make('layouts.contractheader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/payslip/pdf1.blade.php ENDPATH**/ ?>