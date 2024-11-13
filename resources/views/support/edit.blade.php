{{ Form::model($support,array('route' => array('support.update',$support->id),'method'=>'PUT','enctype'=>"multipart/form-data")) }}
<div class="modal-body">
    {{-- start for ai module--}}
    @php
        $plan= \App\Models\Utility::getChatGPTSettings();
    @endphp
    @if($plan->chatgpt == 1)
    <div class="text-end">
        <a href="#" data-size="md" class="btn  btn-primary btn-icon btn-sm" data-ajax-popup-over="true" data-url="{{ route('generate',['support']) }}"
           data-bs-placement="top" data-title="{{ __('Generate content with AI') }}">
            <i class="fas fa-robot"></i> <span>{{__('Generate with AI')}}</span>
        </a>
    </div>
    @endif
    {{-- end for ai module--}}
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('subject', __('Subject'),['class'=>'form-label']) }}
            {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        @if(\Auth::user()->type !='client')
            <div class="form-group col-md-6">
                {{Form::label('user',__('Support for User'),['class'=>'form-label'])}}
                {{Form::select('user',$users,null,array('class'=>'form-control select'))}}
            </div>
        @endif
        <div class="form-group col-md-6">
            {{Form::label('priority',__('Priority'),['class'=>'form-label'])}}
            {{Form::select('priority',$priority,null,array('class'=>'form-control select'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('status',__('Status'),['class'=>'form-label'])}}
            {{Form::select('status',$status,null,array('class'=>'form-control select'))}}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}
            {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>

    </div>
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
        </div>
    
    <div class="form-group col-md-6">
        {{Form::label('attachment',__('Attachment'),['class'=>'form-label'])}}
        <label for="document" class="form-label">
            <input type="file" class="form-control" name="attachment" id="attachment" data-filename="attachment_create">
        </label>
       
        @if($support->attachment)
        @php
            $extension = pathinfo($support->attachment, PATHINFO_EXTENSION);
        @endphp
        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
            {{-- <img id="image" class="mt-2" src="{{ asset(Storage::url('uploads/supports')).'/'.$support->attachment }}" style="width:25%;" alt="{{ asset(Storage::url('uploads/supports')).'/'.$support->attachment }}"/> --}}
            <a href="{{ asset(Storage::url('uploads/supports')).'/'.$support->attachment }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="ti ti-eye text-white"></i> {{ __('View Image') }}
            </a>
        @elseif($extension == 'pdf')
            <a href="{{ asset(Storage::url('uploads/supports')).'/'.$support->attachment }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="ti ti-eye text-white"></i> {{ __('View PDF') }}
            </a>
        @else
            <a href="{{ asset(Storage::url('uploads/supports')).'/'.$support->attachment }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="ti ti-eye text-white"></i> {{ __('View Attachment') }}
            </a>
        @endif
    @endif
</div>
    </div>

    </div>
    <div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
    {{ Form::close() }}
<!-- Modal for Viewing Attachment -->

<script>
    document.getElementById('attachment').onchange = function () {
        var src = URL.createObjectURL(this.files[0]);
        var fileExtension = this.files[0].name.split('.').pop().toLowerCase();
        
        if(['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
            document.getElementById('image').src = src;
        }
    }
</script>
{{-- <script>
    document.getElementById('attachment').onchange = function () {
        var src = URL.createObjectURL(this.files[0]);
        document.getElementById('image').src = src;
        document.getElementById('modalIframe').src = src;
    }
</script> --}}

{{-- <script>
    document.getElementById('attachment').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('image').src = src;
        document.getElementById('modalIframe').src = src;
    }
</script> --}}
