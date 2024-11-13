@extends('layouts.admin')
@section('page-title')
{{ __('Manage Bug Report') }}
@endsection

<style>
    .form-select{
        display:inline-block !important;
        width:auto !important;
    }
</style>
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
<li class="breadcrumb-item">{{ __('Project') }}</li>
<li class="breadcrumb-item">{{ __('Bug Report') }}</li>
@endsection

@section('action-btn')
<div class="float-end">
<label><strong>Project Name : </strong></label>
<select name="p_id" id="p_id" class="form-select mx-1" style="padding-right: 2.5rem;" onchange="ajaxFilterBugView();">
    <?php foreach($projects as $key=>$value)
    { ?>
    <option value="{{$key}}">{{$value}}</option>
    <?php } ?>
</select>
    @if ($view == 'grid')
    <a href="{{ route('bugs.view', 'list') }}" class="btn btn-primary btn-sm p-2 ms-xs-2" data-bs-toggle="tooltip" title="{{ __('List View') }}">
        <span class="btn-inner--text"><i class="ti ti-list"></i></span>
    </a>
    @else
    <a href="{{ route('bugs.view', 'grid') }}" class="btn btn-primary btn-sm p-2 ms-xs-2" data-bs-toggle="tooltip" title="{{ __('Card View') }}">
        <span class="btn-inner--text"><i class="ti ti-table"></i></span>
    </a>
    @endif

    @can('manage project')
    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm p-2 " data-bs-toggle="tooltip" title="{{ __('Back') }}">
        <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
    </a>
    @endcan

</div>
@endsection
@section('content')
    <div class="row min-750" id="bug_list"></div>
@endsection

@push('script-page')
    <script>
        // ready
        $(function () {
            ajaxFilterBugView();
        });
        </script>
        <script>
   
   function ajaxFilterBugView() {
      
   var mainEle = $('#bug_list');
   
   var view = '{{$view}}';
    var project_id=$('#p_id').val();
 
    
    var data = {
       view: view,
       project_id:project_id,
    }

    $.ajax({
        url: '{{ route('project.buglist.view') }}',
        data: data,
        success: function (data) {
        
            mainEle.html(data.html);
           
        }
    });
}
</script>
        @endpush
