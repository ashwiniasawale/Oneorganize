<?php echo e(Form::open(['url' => 'holiday', 'method' => 'post'])); ?>

<div class="modal-body">
    
    <?php
        $plan = \App\Models\Utility::getChatGPTSettings();
    ?>
    <?php if($plan->chatgpt == 1): ?>
        <div class="text-end">
            <a href="#" data-size="md" class="btn btn-primary btn-icon btn-sm" data-ajax-popup-over="true"
                data-url="<?php echo e(route('generate', ['holiday'])); ?>" data-bs-placement="top"
                data-title="<?php echo e(__('Generate content with AI')); ?>">
                <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
            </a>
        </div>
    <?php endif; ?>
    

    <div id="holiday-entries">
        <div class="holiday-entry row">
            <div class="form-group col-md-6">
                <?php echo e(Form::label('occasion0', __('Occasion'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::textarea('occasion[]', null, ['class' => 'form-control', 'placeholder' => __('Enter Occasion'), 'rows' => 6,'required'=>'required'])); ?>

            </div>
            <div class="col-md-6">
            <div class="form-group">
                    <?php echo e(Form::label('holiday_year0', __('Holiday Year'), ['class' => 'form-label'])); ?>

                    <?php
                        $years = range(date('Y') - 10, date('Y') + 10); // Adjust the range as needed
                    ?>

                    <?php echo e(Form::select('holiday_year[]', array_combine($years, $years), isset($_GET['year']) ? $_GET['year'] : date('Y'), ['class' => 'form-control','id'=>'holiday_year0', 'required' => 'required','onchange'=>'check_year_date(0);'])); ?>

                </div>
                <div class="form-group pb-2">
                    <?php echo e(Form::label('date0', __('Start Date'), ['class' => 'form-label'])); ?>

                    <?php echo e(Form::date('date[]', null, ['class' => 'form-control','required'=>'required','id'=>'date0'])); ?>

                </div>
                
            </div>
            <div class="text-end mb-3">
                <!-- <button type="button" class="btn btn-danger btn-sm" onclick="removeHolidayEntry(this)">-
                    <?php echo e(__('Remove')); ?></button> -->
                <button type="button" class="btn btn-secondary btn-sm" onclick="addHolidayEntry()">+
                    <?php echo e(__('Add')); ?></button>
            </div>
            <hr>
        </div>
    </div>

    

    <?php if(isset($settings['google_calendar_enable']) && $settings['google_calendar_enable'] == 'on'): ?>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('synchronize_type', __('Synchronize in Google Calendar ?'), ['class' => 'form-label'])); ?>

            <div class="form-switch">
                <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow"
                    value="google_calendar">
                <label class="form-check-label" for="switch-shadow"></label>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>

<?php echo e(Form::close()); ?>

<script>
    check_year_date(0);
    function check_year_date(id)
    {
      
      var holiday_year=document.getElementById('holiday_year'+id).value;
      
        document.getElementById("date"+id).setAttribute("min", holiday_year+"-01-01");
        document.getElementById("date" + id).setAttribute("max", holiday_year + "-12-31");
    }
    </script>
<script>
    let i = 1;

    function addHolidayEntry() {
        const holidayEntries = document.getElementById('holiday-entries');
        const newEntry = document.createElement('div');
        newEntry.className = 'holiday-entry row';
        newEntry.innerHTML = `
            <div class="form-group col-md-6">
                <?php echo e(Form::label('occasion${i}', __('Occasion'), ['class' => 'form-label'])); ?>

                <?php echo e(Form::textarea('occasion[]', null, ['class' => 'form-control', 'placeholder' => __('Enter Occasion'), 'rows' => 6,'required'=>'required'])); ?>

            </div>
            <div class="col-md-6">
               
               <div class="form-group">
                    <?php echo e(Form::label('holiday_year', __('Holiday Year'), ['class' => 'form-label'])); ?>

                    <?php
                        $years = range(date('Y') - 10, date('Y') + 10); // Adjust the range as needed
                    ?>

                    <?php echo e(Form::select('holiday_year[]', array_combine($years, $years), isset($_GET['year']) ? $_GET['year'] : date('Y'), ['class' => 'form-control', 'id'=>'holiday_year${i}','required' => 'required','onchange'=>'check_year_date(${i});'])); ?>

                </div>
                 <div class="form-group pb-2">
                    <?php echo e(Form::label('date${i}', __('Start Date'), ['class' => 'form-label'])); ?>

                    <?php echo e(Form::date('date[]', null, ['class' => 'form-control','required'=>'required','id'=>'date${i}'])); ?>

                </div>
            </div>
            <div class="text-end mb-3">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeHolidayEntry(this)">- <?php echo e(__('Remove')); ?></button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addHolidayEntry()">+ <?php echo e(__('Add')); ?></button>
            </div>
            <hr>
        `;
        holidayEntries.appendChild(newEntry);
        i++;
    }

    function removeHolidayEntry(button) {
        const entry = button.closest('.holiday-entry');
        entry.remove();
    }
</script>
<?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/holiday/create.blade.php ENDPATH**/ ?>