<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Payslip')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('payslip')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                <?php echo e(Form::open(['route' => ['payslip.store'], 'method' => 'POST', 'id' => 'payslip_form'])); ?>

                <div class="d-flex align-items-center justify-content-end">
                    <div class="col-xl-2 col-lg-3 col-md-3">
                        <div class="btn-box ms-2">
                            <?php echo e(Form::label('month', __('Select Month'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::select('month', $month, date('m'), ['class' => 'form-control select', 'id' => 'month'])); ?>

                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-3">
                        <div class="btn-box ms-2">
                            <?php echo e(Form::label('year', __('Select Year'), ['class' => 'form-label'])); ?>

                            <?php echo e(Form::select('year', $year, date('Y'), ['class' => 'form-control select'])); ?>

                        </div>
                    </div>
                    <div class="col-auto float-end ms-2 mt-4">
                        <button href="#" class="btn  btn-primary"
                            
                            data-bs-toggle="tooltip" title="<?php echo e(__('payslip')); ?>"
                            data-original-title="<?php echo e(__('payslip')); ?>"><?php echo e(__('Generate Payslip')); ?>

</button>
                    </div>
                </div>
                <?php echo e(Form::close()); ?>

            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-start mt-2">
                            <h5><?php echo e(__('Find Employee Payslip')); ?></h5>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex align-items-center justify-content-end ">
                            <div class="col-xl-2 col-lg-3 col-md-4">
                                <div class="btn-box ms-2">
                                    <select class="form-control month_date " name="year" tabindex="-1"
                                        aria-hidden="true">
                                        <option value="--">--</option>
                                        <?php $__currentLoopData = $month; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $mon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $selected = date('m') == $k ? 'selected' : '';
                                            ?>
                                            <option value="<?php echo e($k); ?>" <?php echo e($selected); ?>><?php echo e($mon); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4">
                                <div class="btn-box ms-2 me-2">
                                    <?php echo e(Form::select('year', $year, date('Y'), ['class' => 'form-control year_date '])); ?>

                                </div>
                            </div>
                            <div class="col-auto float-end me-2">
                                <?php echo e(Form::open(['route' => ['payslip.export'], 'method' => 'POST', 'id' => 'payslip_form'])); ?>

                                <input type="hidden" name="filter_month" class="filter_month">
                                <input type="hidden" name="filter_year" class="filter_year">
                                <input type="submit" value="<?php echo e(__('Export')); ?>" class="btn btn-primary">
                                <?php echo e(Form::close()); ?>

                            </div>
                            <!-- <div class="col-auto float-end me-0">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create pay slip')): ?>
                                    <input type="button" value="<?php echo e(__('Bulk Payment')); ?>" class="btn btn-primary"
                                        id="bulk_payment">
                                <?php endif; ?>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="pc-dt-render-column-cells">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Employee Id')); ?></th>
                                <th><?php echo e(__('Name')); ?></th>
                                <!-- <th><?php echo e(__('Payroll Type')); ?></th>
                                <th><?php echo e(__('Salary')); ?></th>
                                <th><?php echo e(__('Net Salary')); ?></th> -->
                                <th><?php echo e(__('Month')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).ready(function() {
            callback();

            function callback() {
                var month = $(".month_date").val();
                var year = $(".year_date").val();

                $('.filter_month').val(month);
                $('.filter_year').val(year);

                if (month == '') {
                    month = '<?php echo e(date('m', strtotime('last month'))); ?>';
                    year = '<?php echo e(date('Y')); ?>';

                    $('.filter_month').val(month);
                    $('.filter_year').val(year);
                }

                var datePicker = year + '-' + month;

                $.ajax({
                    url: '<?php echo e(route('payslip.search_json')); ?>',
                    type: 'POST',
                    data: {
                        "datePicker": datePicker,
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    success: function(data) {
console.log(data);
                        function renderstatus(data, cell, row) {
                            if (data == 'Paid')
                                return '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                            else
                                return '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                        }

                        function renderButton(data, cell, row) {

                            var $div = $(row);
                            employee_id = $div.find('td:eq(0)').text();
                            status = $div.find('td:eq(6)').text();

                            var month = $(".month_date").val();
                            var year = $(".year_date").val();
                            var id = employee_id;
                            var payslip_id = data;

                            var clickToPaid = '';
                            var payslip = '';
                            var view = '';
                            var edit = '';
                            var deleted = '';
                            var form = '';

                            if (data != 0) {
                                var payslip =
                                    '<a href="#" data-url="<?php echo e(url('payslip/pdf/')); ?>/' + id +
                                    '/' + datePicker +
                                    '" data-size="md-pdf"  data-ajax-popup="true" class="btn btn-primary" data-title="<?php echo e(__('Employee Payslip')); ?>">' +
                                    '<?php echo e(__('Payslip')); ?>' + '</a> ';
                            }

                          

                        }
                        var tr = '';
                        if (data.length > 0) {
                            $.each(data, function(indexInArray, valueOfElement) {



                                var status =
                                    '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    valueOfElement[6] + '</a></div>';
                                if (valueOfElement[6] == 'Paid') {
                                    var status =
                                        '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                        valueOfElement[6] + '</a></div>';
                                }

                                var id = valueOfElement[0];
                                var employee_id = valueOfElement[1];
                                var payslip_id = valueOfElement[7];

                                if (valueOfElement[7] != 0) {
                                    var payslip =
                                        '<a href="#" data-url="<?php echo e(url('payslip/pdf/')); ?>/' +
                                        id +
                                        '/' + datePicker +
                                        '" data-size="lg"  data-ajax-popup="true" class=" btn-sm btn btn-warning" data-title="<?php echo e(__('Employee Payslip')); ?>">' +
                                        '<?php echo e(__('Payslip')); ?>' + '</a> ';
                                }
                                
                                    var edit =
                                        '<a href="#" data-url="<?php echo e(url('payslip/editemployee/')); ?>/' +
                                        payslip_id +
                                        '"  data-ajax-popup="true" class="btn-sm btn btn-info" data-title="<?php echo e(__('Edit Employee salary')); ?>">' +
                                        '<?php echo e(__('Edit')); ?>' + '</a>';
                                
                                var url_employee = valueOfElement['url'];

                                tr +=
                                    '<tr> ' +
                                    '<td> <a class="btn btn-outline-primary" href="' +
                                    url_employee + '">' +
                                    valueOfElement[1] + '</a></td> ' +
                                    '<td>' + valueOfElement[2] + '</td> ' +
                                    '<td>' + valueOfElement[8] + '</td>' +
                                   
                                    '<td>' + status + '</td>' +
                                    '<td>' + payslip + '</td>' +
                                    '</tr>';
                            });
                        } else {
                           
                        }

                        $('#pc-dt-render-column-cells tbody').html(tr);
                      
                        new simpleDatatables.DataTable('#pc-dt-render-column-cells');
                        

                    },
                    error: function(data) {

                    }

                });

            }

            $(document).on("change", ".month_date,.year_date", function() {
              
                callback();
            });

           
          
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/payslip/index.blade.php ENDPATH**/ ?>