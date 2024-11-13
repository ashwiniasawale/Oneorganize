<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Create Employee')); ?> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(url('employee')); ?>"><?php echo e(__('Employee')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Create Employee')); ?></li>
    
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
   <div class="float-end">
      <a href="<?php echo e(url('employee')); ?>" class="btn btn-primary btn-sm p-2" data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
      <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
      </a>
   </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

<style>
 
    .d-none {
        display: none;
    }
    .toggle-password {
        position: relative;
        left: 60%;
    top: 3em;
        transform: translateY(-50%);
        cursor: pointer;
    }

      /* Media query for screens less than 786 pixels */
      /* @media (min-width: 467px) {
        .toggle-password {
            left: 21em;
        }
    } */
</style>
<div class="row">


                <?php $__env->startPush('script-page'); ?>
    <script>
        $(document).ready(function() {
            // Hide the validation errors after 30 seconds
            setTimeout(function() {
                $('#validation-errors').fadeOut('slow');
            }, 10000); // 30 seconds
        });
    </script>
<?php $__env->stopPush(); ?>
    <div class="">
        <div class="">
            <div class="row">
            </div>
            <?php echo e(Form::open(['route' => ['employee.store'], 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5><?php echo e(__('Personal Detail')); ?></h5>
                            
                        </div>
                        
                        <div class="card-body">
                            <div class="row">
                            <div class="form-group col-md-3">
                                    <?php echo Form::label('salutation', __('Salutation'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <select class="form-control  " id="choices-multiple" name="salutation" required>
                                        <option value="">-Select Salutation-</option>
                                        <option value="Mr.">Mr</option>
                                        <option value="Miss.">Miss</option>
                                        <option value="Mrs.">Mrs</option>
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
                                </div>
                               
                                <div class="form-group col-md-5">
                                    <?php echo Form::label('name', __('Name'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::text('name', old('name'), ['class' => 'form-control', 'required' => 'required' ,'placeholder'=>'Enter employee name']); ?>

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
                                    <?php echo Form::label('phone', __('Phone'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo e(Form::text('phone', old('phone'), ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter Employee Phone number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput'])); ?>

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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo Form::label('dob', __('Date of Birth'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                        <?php echo e(Form::date('dob', null, ['class' => 'form-control ', 'required' => 'required', 'autocomplete' => 'off','placeholder'=>'Select Date of Birth'])); ?>

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

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo Form::label('gender', __('Gender'), ['class' => 'form-label' , 'required' => 'required' ]); ?><span class="text-danger pl-1">*</span>
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_male" value="Male" name="gender"
                                                    class="form-check-input" checked>
                                                <label class="form-check-label " for="g_male"><?php echo e(__('Male')); ?></label>
                                            </div>
                                            <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="gender"
                                                    class="form-check-input">
                                                <label class="form-check-label "
                                                    for="g_female"><?php echo e(__('Female')); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('email', __('Email'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <?php echo Form::email('email', old('email'), ['autocomplete'=>'off','class' => 'form-control', 'required' => 'required' ,'placeholder'=>'Enter employee email']); ?>

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
                                <div class="form-group col-md-6">

                                    
                               
                                    <?php echo Form::label('password', __('Password'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                    <svg class="eye-slash toggle-password" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512"><path d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"></path></svg>
                                    <svg class="eye toggle-password d-none" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"></path></svg>
                                    <?php echo Form::password('password',['class' => 'form-control', 'autocomplete'=>'off','required' => 'required' ,'id' =>'input-password','placeholder'=>'Enter employee new password']); ?>

                                    <?php $__errorArgs = ['password'];
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
                            <div class="form-group">
                                <?php echo Form::label('address', __('Address'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                <?php echo Form::textarea('address', old('address'), ['class' => 'form-control', 'rows' => 4 ,'placeholder'=>'Enter employee address' , 'required' => 'required', 'maxlength' => '255']); ?>

                               
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
                <div class="col-md-6">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5><?php echo e(__('Company Detail')); ?></h5>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            <div class="row">
                                <?php echo csrf_field(); ?>
                                <div class="form-group ">
                                    <?php echo Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']); ?>

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
                                    <?php echo e(Form::label('branch_id', __('Select Branch*'), ['class' => 'form-label'])); ?>

                                    <div class="form-icon-user">
                                        <?php echo e(Form::select('branch_id', $branches, null, ['required'=>'required','onchange'=>'getDepartment(this.value);','class' => 'form-control select2', 'placeholder' => 'Select Branch'])); ?>

                                    </div>
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
                                    <?php echo e(Form::label('department_id', __('Select Department*'), ['class' => 'form-label'])); ?>

                                    <div class="form-icon-user department_div">

                                        <?php echo e(Form::select('department_id', $departments, null, ['required'=>'required','onchange'=>'getDesignation(this.value);','class' => 'form-control select2 department_id', 'id' => 'department_id' , 'placeholder' => 'Select Department'])); ?>

                                    </div>
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
                                    <?php echo e(Form::label('designation_id', __('Select Designation'), ['class' => 'form-label'])); ?>


                                    <div class="form-icon-user">
                                    <div class="designation_div">
                                    <?php echo e(Form::select('designation_id', $designations, null, ['required'=>'required','class' => 'form-control select2 designation_id', 'id' => 'designation_id' , 'placeholder' => 'Select Designation'])); ?>

                                
                                           
                                        </div> 
                                        
                                    </div>
                                    <?php $__errorArgs = ['designation_id'];
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
                                    <?php echo Form::label('company_doj', __('Company Date Of Joining'), ['class' => '  form-label']); ?>

                                    <?php echo e(Form::date('company_doj', null, ['class' => 'form-control ', 'required' => 'required', 'autocomplete' => 'off' ,'placeholder'=>'Select company date of joining'])); ?>

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
                                <!-- <?php if (Auth::user()->type == 'HR' || Auth::user()->type=='company')
                                    { ?>
                                    <div class="form-group col-md-6">
                                    <?php echo Form::label('annual_salary', 'Salary (e.g. if 3 LPA then 300000)',['class'=>'form-label']); ?>

                                    <?php echo Form::number('annual_salary',null,['class'=>'form-control','required'=>'required']); ?>

                                    </div>
                                    <?php } ?> -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 ">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5><?php echo e(__('Document')); ?></h6>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row">
                                    <div class="form-group col-12 d-flex">
                                        <div class="float-left col-4">
                                            <label for="document"
                                                class="float-left pt-1 form-label"><?php echo e($document->name); ?> <?php if($document->is_required == 1): ?>
                                                    <span class="text-danger">*</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                        <div class="float-right col-8">
                                            <input type="hidden" name="emp_doc_id[<?php echo e($document->id); ?>]" id=""
                                                value="<?php echo e($document->id); ?>">
                                            <div class="choose-files">
                                                <label for="document[<?php echo e($document->id); ?>]">
                                                    <div class=" bg-primary document "> <i
                                                            class="ti ti-upload "></i><?php echo e(__('Choose file here')); ?>

                                                    </div>
                                                    <input type="file"
                                                    required
                                                        class="form-control file  d-none <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        <?php if($document->is_required == 1): ?> required <?php endif; ?>
                                                        name="document[<?php echo e($document->id); ?>]" id="document[<?php echo e($document->id); ?>]"
                                                        data-filename="<?php echo e($document->id . '_filename'); ?>" onchange="document.getElementById('<?php echo e('blah'.$key); ?>').src = window.URL.createObjectURL(this.files[0])">
                                                </label>
                                                <img id="<?php echo e('blah'.$key); ?>" src=""  width="50%" />

                                            </div>

                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5><?php echo e(__('Bank Account Detail')); ?></h5>
                        </div>
                        <div class="card-body employee-detail-create-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <?php echo Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('account_holder_name', old('account_holder_name'), ['class' => 'form-control' ,'placeholder'=>'Enter account holder name']); ?>

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
                                    <?php echo Form::label('account_number', __('Account Number'), ['class' => 'form-label']); ?>

                                    
                                    <?php echo e(Form::number('account_number', old('account_number'), ['class' => 'form-control', 'required' => 'required','pattern'=>"\d{10,12}",'placeholder' => __('Enter account number'), 'minlength'=>'10','maxlength' => '12','id' => 'phoneInput'])); ?>

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
                                    <?php echo Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('bank_name', old('bank_name'), ['class' => 'form-control'  ,'placeholder'=>'Enter bank name']); ?>

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
                                    <?php echo Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('bank_identifier_code', old('bank_identifier_code'), ['class' => 'form-control' ,'placeholder'=>'Enter bank identifier code']); ?>

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
                                    <?php echo Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('branch_location', old('branch_location'), ['class' => 'form-control' ,'placeholder'=>'Enter branch location']); ?>

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
                                    <?php echo Form::label('tax_payer_id', __('Tax Payer Id (PANCARD)'), ['class' => 'form-label']); ?>

                                    <?php echo Form::text('tax_payer_id', old('tax_payer_id'), ['class' => 'form-control' ,'placeholder'=>'Enter tax payer id']); ?>

                                    <?php $__errorArgs = ['tax_payer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger"><?php echo e($message); ?></div><?php unset($message);
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

        </div>

        <div class="float-end">
        <a  href="<?php echo e(url('employee')); ?>" class="btn btn-light"  ><?php echo e('Cancel'); ?></a>
         
            <button type="submit" class="btn  btn-primary"><?php echo e('Create'); ?></button>
        </div>
        </form>
    </div>
     <!-- Display validation errors -->
    
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<script>
    $(document).ready(function() {
    // Initialize Select2 for all select elements with the class 'select2'
    $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: true,
        width: '100%'
    });
});
    document.getElementById('phoneInput').addEventListener('input', function (e) {
        // Replace any non-digit characters
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    </script>
<script>
    $(document).ready(function() {
        $(".toggle-password").click(function() {
            $(this).toggleClass("d-none");
            $(".toggle-password").not(this).toggleClass("d-none");
            var input = $("#input-password");
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

      
    });
</script>


    <script>
        $('input[type="file"]').change(function(e) {
            var file = e.target.files[0].name;
            var file_name = $(this).attr('data-filename');
            $('.' + file_name).append(file);
        });
    </script>
    <script>
       function getDepartment(bid) {

$.ajax({
    url: '<?php echo e(route('employee.dept_json')); ?>',
    type: 'POST',
    data: {
        "branch_id": bid,
        "_token": "<?php echo e(csrf_token()); ?>",
    },
    success: function(data) {

        $('.department_id').empty();
        var emp_selct = ` <select class="form-control  department_id" name="department_id" id="choices-multiple"
                                placeholder="Select Designation" onchange='getDesignation(this.value);' >
                                </select>`;
        $('.department_div').html(emp_selct);
        $('.department_id').append('<option value=""> <?php echo e(__('Select Department')); ?> </option>');
        $.each(data, function(key, value) {
            $('.department_id').append('<option value="' + key + '">' + value +
                '</option>');
        });
        new Choices('#choices-multiple', {
            removeItemButton: true,
        });


    }
});
}
        function getDesignation(did) {
            
            $.ajax({
                url: '<?php echo e(route('employee.json')); ?>',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {

                    $('.designation_id').empty();
                    var emp_selct = ` <select class="form-control designation_id " name="designation_id" id="choices-multiple"
                                            placeholder="Select Designation" required>
                                            </select>`;
                    $('.designation_div').html(emp_selct);

                    $('.designation_id').append('<option value=""> <?php echo e(__('--Select Designation--')); ?> </option>');
                    $.each(data, function(key, value) {
                        $('.designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });


                }
            });
        }
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/employee/create.blade.php ENDPATH**/ ?>