<?php echo e(Form::open(['url' => 'employee_assets_store', 'method' => 'post','id'=>'create_employee_assets'])); ?>


<div class="modal-body">
  <div class="row">
  <div class="form-group col-md-6">
  <?php echo e(Form::label('employee_id', __('Employee'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('employee_id', $employee,null, array('class' => 'form-control select2','required'=>'required'))); ?>

          </div>
    <div id="asset-entries">
        <div class="asset-entry row">
            <div class="form-group col-md-6">
                <?php echo e(Form::label('asset_id0', __('Asset Name'), ['class' => 'form-label'])); ?>

                <select id="asset_id" name="asset_id[]" class="form-control select2" required>
                    <?php 
                    if(!empty($asset))
                    {
                    foreach($asset as $a)
                    {
                        ?>
                        <option value="<?php echo e($a->id); ?>"><?php echo e($a->name); ?>(<?php echo e($a->serial_number); ?>)</option>
                        <?php
                    }
                }?>
                </select>
            </div>
         
            <div class="text-end mb-1">
              
                <button type="button" class="btn btn-secondary btn-sm" onclick="addAssetEntry()">+
                    <?php echo e(__('Add')); ?></button>
            </div>
            <hr>
        </div>
    </div>
  </div>

  

</div>

<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="employee_asset_submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>

<?php echo e(Form::close()); ?>


<script>
       const assets = <?php echo json_encode($asset, 15, 512) ?>;

    let i = 1;

    function addAssetEntry() {
        const assetEntries = document.getElementById('asset-entries');
        const newEntry = document.createElement('div');
        newEntry.className = 'asset-entry row';
        const optionsHtml = assets.map(asset => 
                `<option value="${asset.id}">${asset.name} (${asset.serial_number})</option>`
            ).join('');
        newEntry.innerHTML = `
            <div class="form-group col-md-6">
                <?php echo e(Form::label('asset_id${i}', __('Asset Name'), ['class'=> 'form-label'])); ?>

                  <select class="form-control select2" id="asset_id${i}" name="asset_id[]" required>
                    <option value="">--Select Asset--</option> 
                     ${optionsHtml}
            ).join('');     
            </select>
            </div>

          
            <div class="text-end mb-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeAssetEntry(this)">- <?php echo e(__('Remove')); ?></button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addAssetEntry()">+ <?php echo e(__('Add')); ?></button>
            </div>
            <hr>
        `;
        assetEntries.appendChild(newEntry);
        i++;
    }

    function removeAssetEntry(button) {
        const entry = button.closest('.asset-entry');
        entry.remove();
    }
</script>

<script>
  
    $(document).ready(function () {
        $('#create_employee_assets').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
          
            // Send AJAX request
            $("#employee_asset_submit").attr("disabled", true);
            $("#loader").css("display", "flex");

            $.ajax({
                type: $(this).attr('method'), // Get the HTTP method (POST or GET)
                url: $(this).attr('action'), // Get the form's action attribute value
                data: formData, // Set the form data
                processData: false,  // Important! Don't process the data
                contentType: false,  
                success: function (response) {
               
                 console.log(response); // Log the response to the console
                 $("#loader").css("display", "none");
                 $("#employee_asset_submit").attr("disabled", false);
                  if(response.success)
                  {
                    show_toastr('Success', response.success, 'success');
                   // console.log(response.tmp_task)
                   $("#att_table").load(" #att_table");
                         $('#commonModal').modal('hide');
                         
                  }else{
                  
                    show_toastr('Error', response.error, 'error');
                  }
              
                   
                  
                },
                error: function (xhr, status, error) {
                    // Handle errors
                    console.error(error); // Log the error to the console
                }
            });
        });
    });

   
</script><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/employee_assets/create.blade.php ENDPATH**/ ?>