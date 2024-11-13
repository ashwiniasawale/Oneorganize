{{ Form::open(['route' => 'employee_assets_update', 'method' => 'post','id'=>'update_employee_assets']) }}

<div class="modal-body">
  <div class="row">
  <div class="form-group col-md-6">
    <input type="hidden" name="ids" id="ids" value="{{$emp_asset->id}}">
  {{ Form::label('employee_id', __('Employee'),['class'=>'form-label']) }}
    <input type="text" class="form-control" disabled value="{{$employee->name}}" name="employee_id" id="employee_id">    
</div>

    <div id="asset-entries">
       
       
        <?php 
        $i=0;
          foreach (explode(',', $emp_asset->asset_id) as $key_user) {
           
       ?>
        <div class="asset-entry row">
            <div class="form-group col-md-6">
                {{ Form::label('asset_id0', __('Asset Name'), ['class' => 'form-label']) }}
                <?php $get_asset = App\Models\Asset::select('id','serial_number','name')->where('id','=',$key_user)->first();
          
          ?>
          <select id="asset_id{{$i}}" name="asset_id[]" class="form-control" >
            <option value="{{$get_asset->id}}"> {{ $get_asset->name }}({{ $get_asset->serial_number }})</option>
          </select>
         </li>
           
            </div>
         
            <div class="text-end mb-3">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeAssetEntry(this)">-
                    {{ __('Remove') }}</button>
               
            </div>
            <hr>
            </div>
          <?php $i++; }  ?>
         
        
        <div class="text-end mb-1">
              
              <button type="button" class="btn btn-secondary btn-sm" onclick="addAssetEntry()">+
                  {{ __('Add') }}</button>
          </div>
    </div>
  </div>

  

</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="update_asset_submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
<script>
    // Make sure `@json($asset)` provides the correct structure
    const assets = @json($asset);

    let i = {{ count(explode(',', $emp_asset->asset_id)) }};

    function addAssetEntry() {
        const assetEntries = document.getElementById('asset-entries');
        const newEntry = document.createElement('div');
        newEntry.className = 'asset-entry row';

        const optionsHtml = assets.map(asset =>
            `<option value="${asset.id}">${asset.name} (${asset.serial_number})</option>`
        ).join('');

        newEntry.innerHTML = `
            <div class="form-group col-md-6">
                <label for="asset_id${i}" class="form-label">Asset Name</label>
                <select class="form-control select2" id="asset_id${i}" name="asset_id[]" required>
                    <option value="">--Select Asset--</option>
                    ${optionsHtml}
                </select>
            </div>
            <div class="text-end mb-1">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeAssetEntry(this)">- {{ __('Remove') }}</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addAssetEntry()">+ {{ __('Add') }}</button>
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
        $('#update_employee_assets').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission

            // Gather form data
            var formData = new FormData(this);
          
            // Send AJAX request
            $("#update_asset_submit").attr("disabled", true);
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
                 $("#update_asset_submit").attr("disabled", false);
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

   
</script>