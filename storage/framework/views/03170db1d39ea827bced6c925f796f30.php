<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Edit Employee')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('employee.index')); ?>"><?php echo e(__('Employee')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e($employeesId); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <?php echo e(Form::model($employee, array('route' => array('employee.update', $employee->id), 'method' => 'PUT' , 'enctype' => 'multipart/form-data'))); ?>

            <?php echo csrf_field(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 ">
            <div class="card emp_details">
                <div class="card-header"><h6 class="mb-0"><?php echo e(__('Personal Detail')); ?></h6></div>
                <div class="card-body employee-detail-edit-body">

                    <div class="row">
                    <!-- <div class="form-group col-md-3">
                                    <?php echo Form::label('salutation', __('Salutation'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <select class="form-control" name="salutation" required>
                                        <option value="">-Select Salutation-</option>
                                        <option value="Mr.">Mr</option>
                                        <option value="Miss.">Miss</option>
                                        <option value="Mrs.">Mrs.</option>
                                    </select>
                                    <?php $__errorArgs = ['salutation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div> -->
                        <div class="form-group col-md-5">
                            <?php echo Form::label('name', __('Name'),['class'=>'form-label']); ?><span class="text-danger pl-1">*</span>
                            <?php echo Form::text('name', null, ['class' => 'form-control','required' => 'required']); ?>

                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group col-md-4">
                            <?php echo Form::label('phone', __('Phone'),['class'=>'form-label']); ?><span class="text-danger pl-1">*</span>
                            
                            <?php echo e(Form::text('phone',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Employee Phone number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput'])); ?>

                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
<div class="text-danger"><?php echo e($message); ?></div>
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group col-md-6">

                            <?php echo Form::label('dob', __('Date of Birth'),['class'=>'form-label']); ?><span class="text-danger pl-1">*</span>
                            <?php echo Form::date('dob', null, ['class' => 'form-control']); ?>

                            <?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group col-md-6">
                            <?php echo Form::label('gender', __('Gender'),['class'=>'form-label']); ?><span class="text-danger pl-1">*</span>
                            <div class="d-flex radio-check mt-2">
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="g_male" value="Male" name="gender" class="form-check-input" <?php echo e(($employee->gender == 'Male')?'checked':''); ?>>
                                    <label class="form-check-label" for="g_male"><?php echo e(__('Male')); ?></label>
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="g_female" value="Female" name="gender" class="form-check-input" <?php echo e(($employee->gender == 'Female')?'checked':''); ?>>
                                    <label class="form-check-label" for="g_female"><?php echo e(__('Female')); ?></label>
                                </div>

                            </div>
                        </div> 
                        <?php if (Auth::user()->type == 'Employee')
                        { ?>
                        <div class="form-group col-md-6">
                                    <?php echo Form::label('email', __('Email'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                
                                    <?php echo Form::email('email', old('email'), ['class' => 'form-control', 'required' => 'required' ,'readonly'=>'readonly','placeholder'=>'Enter employee email']); ?>

                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <?php }else{ ?>
                                    <div class="form-group col-md-6">
                                    <?php echo Form::label('email', __('Email'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                
                                    <?php echo Form::email('email', old('email'), ['class' => 'form-control', 'required' => 'required' ,'placeholder'=>'Enter employee email']); ?>

                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <?php } ?>
                    </div>
                    <div class="form-group">
                        <?php echo Form::label('address', __('Address'),['class'=>'form-label']); ?><span class="text-danger pl-1">*</span>
                        <?php echo Form::textarea('address',null, ['class' => 'form-control','rows'=>4,'required' => 'required', 'maxlength' => '255']); ?>

                        <span id="address-counter" class="text-danger " >0</span>/255 characters
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                   
                </div>
            </div>
        </div>
     
            <div class="col-md-6 ">
                <div class="card emp_details">
                    <div class="card-header"><h6 class="mb-0"><?php echo e(__('Company Detail')); ?></h6></div>
                    <div class="card-body employee-detail-edit-body">
                        <div class="row">
                            <?php echo csrf_field(); ?>
                            <div class="form-group col-md-12">
                                <?php echo Form::label('employee_id', __('Employee ID'),['class'=>'form-label']); ?>

                                <?php echo Form::text('employee_id',$employeesId, ['class' => 'form-control','disabled'=>'disabled']); ?>

                                <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('branch_id', __('Branch'),['class'=>'form-label'])); ?>

                                <?php echo e(Form::select('branch_id', $branches,null, array('class' => 'form-control select2','required'=>'required','id' => 'branch_id'))); ?>

                                <?php $__errorArgs = ['branch_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('department_id', __('Department'),['class'=>'form-label'])); ?>

                                <?php echo e(Form::select('department_id', $departments,null, array('class' => 'form-control select2','required'=>'required','id' => 'department_id'))); ?>

                                <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                

                            </div>
                            <div class="form-group col-md-6">
                                <?php echo e(Form::label('designation_id', __('Designation'),['class'=>'form-label'])); ?>

                                <select class="select2 form-control " id="designation_id" name="designation_id" ></select>

                            </div>
                            <div class="form-group col-md-6">
                                <?php echo Form::label('company_doj', 'Company Date Of Joining',['class'=>'form-label']); ?>

                                <?php echo Form::date('company_doj', null, ['class' => 'form-control ','required' => 'required']); ?>

                                <?php $__errorArgs = ['company_doj'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <?php if (Auth::user()->type == 'HR' || Auth::user()->type=='company')
                            { ?>
                            <div class="form-group col-md-6">
                            <?php echo Form::label('annual_salary', 'Salary (e.g. if 3 LPA then 300000)',['class'=>'form-label']); ?>

                            <?php echo Form::number('annual_salary',null,['class'=>'form-control','required'=>'required']); ?>

                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
       
    </div>
   
        <div class="row">
            <div class="col-md-6 ">
                <div class="card emp_details">
                    <div class="card-header"><h6 class="mb-0"><?php echo e(__('Document')); ?></h6></div>
                    <div class="card-body employee-detail-edit-body">
                        <?php
                            $employeedoc = $employee->documents()->pluck('document_value',__('document_id'));
                        ?>

                        <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row">
                                <div class="form-group col-12">
                                    <div class="float-left col-4">
                                        <label for="document" class="float-left pt-1 form-label"><?php echo e($document->name); ?> <?php if($document->is_required == 1): ?> <span class="text-danger">*</span> <?php endif; ?></label>
                                    </div>
                                    <div class="float-right col-4">
                                        <input type="hidden" name="emp_doc_id[<?php echo e($document->id); ?>]" id="" value="<?php echo e($document->id); ?>">
                                        <div class="choose-file form-group">
                                            <label for="document[<?php echo e($document->id); ?>]">
                                                <input class="form-control <?php if(!empty($employeedoc[$document->id])): ?> float-left <?php endif; ?> <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> border-0" <?php if($document->is_required == 1 && empty($employeedoc[$document->id]) ): ?> required <?php endif; ?> name="document[<?php echo e($document->id); ?>]"  onchange="document.getElementById('<?php echo e('blah'.$key); ?>').src = window.URL.createObjectURL(this.files[0])" type="file"  data-filename="<?php echo e($document->id.'_filename'); ?>">
                                            </label>
                                            <p class="<?php echo e($document->id.'_filename'); ?>"></p>

                                            <?php
                                                $logo=\App\Models\Utility::get_file('uploads/document/');
                                            ?>


                                                <img target="_blank" id="<?php echo e('blah'.$key); ?>" src="<?php echo e((isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id])?$logo.'/'.$employeedoc[$document->id]:'')); ?>"  width="25%" />
                                                <a id="openModalBtn" data-size="lg" data-title="<?php echo e(__('View Document')); ?>" data-bs-toggle="modal" data-bs-target="#employeeModal<?php echo e('blah'.$key); ?>" title="<?php echo e(__('View')); ?>" class="btn btn-sm btn-primary">
                                                    <i class="ti ti-eye text-white"></i>
                                                </a>

                                            <!-- Modal -->
                                            <div class="modal fade " id="employeeModal<?php echo e('blah'.$key); ?>" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                    <div class="modal-body" style="height:500px;">
                                                        <iframe id="inlineFrameExample<?php echo e('blah'.$key); ?>"
                                                        frameborder="0"
                                                        allowfullscreen
                                                        src="<?php 
                                                            if (isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id])) {
                                                                echo $logo . '/' . $employeedoc[$document->id];
                                                            } 
                                                        ?>"  width="100%"
                                                        height="400">
                                                        </iframe>
                                                    </div>
                                                    
                                                        <!-- Modal content goes here -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>






                                    </div>

                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card emp_details">
                    <div class="card-header"><h6 class="mb-0"><?php echo e(__('Bank Account Detail')); ?></h6></div>
                    <div class="card-body employee-detail-edit-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <?php echo Form::label('account_holder_name', __('Account Holder Name'),['class'=>'form-label']); ?>

                                <?php echo Form::text('account_holder_name', null, ['class' => 'form-control']); ?>

                                <?php $__errorArgs = ['account_holder_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo Form::label('account_number', __('Account Number'),['class'=>'form-label']); ?>

                                
                                <?php echo e(Form::text('account_number',null, ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter account number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput'])); ?>

                                 
                                <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo Form::label('bank_name', __('Bank Name'),['class'=>'form-label']); ?>

                                <?php echo Form::text('bank_name', null, ['class' => 'form-control']); ?>

                                <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo Form::label('bank_identifier_code', __('Bank Identifier Code'),['class'=>'form-label']); ?>

                                <?php echo Form::text('bank_identifier_code',null, ['class' => 'form-control']); ?>

                                <?php $__errorArgs = ['bank_identifier_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo Form::label('branch_location', __('Branch Location'),['class'=>'form-label']); ?>

                                <?php echo Form::text('branch_location',null, ['class' => 'form-control']); ?>

                                <?php $__errorArgs = ['branch_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?php echo Form::label('tax_payer_id', __('Tax Payer Id (PANCARD)'),['class'=>'form-label']); ?>

                                <?php echo Form::text('tax_payer_id',null, ['class' => 'form-control']); ?>

                                <?php $__errorArgs = ['tax_payer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-danger"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                    <?php echo Form::label('UAN_NO',__('UAN NO'),['class'=>'form-label']); ?>

                                    <?php echo Form::text('UAN_NO',old('UAN_NO'),['class'=>'form-control','placeholder'=>'Enter UAN NO']); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  

        <div class="row">
            <div class="col-12">
                <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary float-end">
            </div>
        </div>
 
    <div class="row">
        <div class="col-12">
            <?php echo Form::close(); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<script>
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>
    <script type="text/javascript">

        $(document).on('change', '#branch_id', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(branch_id)
        {
            var data = {
                "branch_id": branch_id,
                "_token": "<?php echo e(csrf_token()); ?>",
            }

            $.ajax({
                url: '<?php echo e(route('employee.getdepartment')); ?>',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append('<option value="" disabled><?php echo e(__('Select any Department')); ?></option>');

                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#department_id').val('');
                }
            });
        }
    </script>
    <script type="text/javascript">

        function getDesignation(did) {
            $.ajax({
                url: '<?php echo e(route('employee.json')); ?>',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function (data) {
                    $('#designation_id').empty();
                    $('#designation_id').append('<option value="">Select any Designation</option>');
                    $.each(data, function (key, value) {
                        var select = '';
                        if (key == '<?php echo e($employee->designation_id); ?>') {
                            select = 'selected';
                        }

                        $('#designation_id').append('<option value="' + key + '"  ' + select + '>' + value + '</option>');
                    });
                }
            });
        }

        $(document).ready(function () {
            var d_id = $('#department_id').val();
            var designation_id = '<?php echo e($employee->designation_id); ?>';
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department_id]', function () {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

    </script>
<?php $__env->stopPush(); ?>
<script>
        document.addEventListener("DOMContentLoaded", function() {
            var addressTextarea = document.getElementById('address');
            var addressCounter = document.getElementById('address-counter');

            addressTextarea.addEventListener('input', function() {
                var addressLength = addressTextarea.value.length;
                addressCounter.textContent = addressLength;
            });
        });
    </script>
    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var addressTextarea = document.getElementById('address');
            var addressCounter = document.getElementById('address-counter');

            addressTextarea.addEventListener('input', function() {
                var addressLength = addressTextarea.value.length;
                addressCounter.textContent = addressLength;
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/employee/edit.blade.php ENDPATH**/ ?>