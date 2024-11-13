{{ Form::open(['url' => 'trainer', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <!-- @if ($errors->any())
<div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
                        </ul>
                    </div>
@endif
  -->
        <!-- <div id="modalContainer">
 
  {{ session('modalData.firstname') }}
  </div>  -->
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                {{ Form::select('branch', $branches, null, ['class' => 'form-control select', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('firstname', __('First Name'), ['class' => 'form-label']) }}
                {{ Form::text('firstname', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter First Name')]) }}
                @error('firstname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('lastname', __('Last Name'), ['class' => 'form-label']) }}
                {{ Form::text('lastname', old('lastname'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Last Name')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('contact', __('Contact'), ['class' => 'form-label']) }}
                {{ Form::text('contact', null, ['class' => 'form-control phoneNumber', 'required' => 'required', 'placeholder' => __('Enter Contact')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter email')]) }}
            </div>
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('expertise', __('Expertise'), ['class' => 'form-label']) }}
            {{ Form::textarea('expertise', null, ['class' => 'form-control', 'placeholder' => __('Expertise')]) }}
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
            {{ Form::textarea('address', null, ['class' => 'form-control', 'placeholder' => __('Address')]) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
    //    @if (session('modalStayOpen'))

    //         var errors = @json($errors->all());
    //         var errorHtml = '<div class="alert alert-danger"><ul>';
    //         errors.forEach(function(error) {
    //             errorHtml += '<li>' + error + '</li>';
    //         });
    //         errorHtml += '</ul></div>';
    //         $('#modalContainer').prepend(errorHtml);
    //     @endif
</script>

<script>
    $(document).ready(function() {
        $('#trainer-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '{{ route('trainer.store') }}',
                data: $(this).serialize(),
                success: function(response) {
                    toastr.success('Trainer successfully created.');
                    $('#trainer-form')[0].reset();
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            toastr.error(errors[key][0]);
                        }
                    }
                }
            });













        });



        $('.phoneNumber').on('keypress', function(event) {

            var charCode = (event.which) ? event.which : event.keyCode;
            // Allow: backspace, delete, tab, escape, enter
            if ($.inArray(charCode, [8, 9, 27, 13]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (charCode === 65 && (event.ctrlKey === true || event.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (charCode >= 35 && charCode <= 40)) {
                return;
            }
            // Ensure that it is a number and the input length is less than 10
            if ((charCode < 48 || charCode > 57) || $(this).val().length >= 10) {
                event.preventDefault();
            }
        });



        $('.phoneNumber').on('input', function() {
            // Allow only numbers starting with 6, 7, 8, or 9 and trim the input if length exceeds 10 digits
            var value = $(this).val();
            if (value.length > 10) {
                $(this).val(value.slice(0, 10));
            }
            if (value.length > 0 && !/^[6789]/.test(value)) {
                $(this).val('');
            }
        });
        // Ensure that it is a number and the input length is less than 10
        if ((charCode < 48 || charCode > 57) || $(this).val().length >= 10) {
            event.preventDefault();
        }




        $('.phoneNumber').on('input', function() {
            // Allow only numbers starting with 6, 7, 8, or 9 and trim the input if length exceeds 10 digits
            var value = $(this).val();
            if (value.length > 10) {
                $(this).val(value.slice(0, 10));
            }
            if (value.length > 0 && !/^[6789]/.test(value)) {
                $(this).val('');
            }
        });
        // Ensure that it is a number and the input length is less than 10
        if ((charCode < 48 || charCode > 57) || $(this).val().length >= 10) {
            event.preventDefault();
        }
    });
</script>
