{{ Form::model($expense, ['route' => ['projects.expenses.update', [$project->id, $expense->id]], 'id' => 'edit_expense', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-12 col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group">
                {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                {{ Form::date('date', null, ['class' => 'form-control ', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                <div class="form-group price-input input-group search-form">
                    <span class="input-group-text bg-transparent">{{ \Auth::user()->currencySymbol() }}</span>
                    {{ Form::number('amount', null, ['class' => 'form-control', 'required' => 'required', 'min' => '0']) }}
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group">
                {{ Form::label('task_id', __('Task'), ['class' => 'form-label']) }}
                <select class="form-control select" name="task_id" id="task_id">
                    <option class="text-muted" value="0" disabled selected> Choose Task </option>
                    @foreach ($project->tasks as $task)
                        <option value="{{ $task->id }}" {{ $task->id == $expense->task_id ? 'selected' : '' }}>
                            {{ $task->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                <small
                    class="form-text text-muted mb-2 mt-0">{{ __('This textarea will autosize while you type') }}</small>
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '1', 'data-toggle' => 'autosize']) }}
            </div>
        </div>
        <div class="col-12 col-md-12">
            {{ Form::label('attachment', __('Attachment'), ['class' => 'form-label']) }}
            <div class="choose-file form-group">
                <label for="attachment" class="form-label">
                    <div>{{ __('Choose file here') }}</div>
                    <input type="file" class="form-control" name="attachment" id="attachment"
                        data-filename="attachment_create">
                </label>
                <p class="attachment_create"></p>

                <label for="document[{{ $document->id }}]">
                    <input
                        class="form-control @if (!empty($employeedoc[$document->id])) float-left @endif @error('document') is-invalid @enderror border-0"
                        @if ($document->is_required == 1 && empty($employeedoc[$document->id])) required @endif name="document[{{ $document->id }}]"
                        onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])"
                        type="file" data-filename="{{ $document->id . '_filename' }}">
                </label>
                <p class="{{ $document->id . '_filename' }}"></p>

                @php
                    $logo = \App\Models\Utility::get_file('uploads/document/');
                @endphp

                {{--                                            <img id="{{'blah'.$key}}" src=""  width="25%" /> --}}
                <img target="_blank" id="{{ 'blah' . $key }}"
                    src="{{ isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '' }}"
                    width="25%" />
                <a id="openModalBtn" data-size="lg" data-title="{{ __('View Document') }}" data-bs-toggle="modal"
                    data-bs-target="#employeeModal{{ 'blah' . $key }}" title="{{ __('View') }}"
                    class="btn btn-sm btn-primary">
                    <i class="ti ti-eye text-white"></i>
                </a>

                <!-- Modal -->
                <div class="modal fade " id="employeeModal{{ 'blah' . $key }}" tabindex="-1"
                    aria-labelledby="employeeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body" style="height:500px;">
                                <iframe id="inlineFrameExample{{ 'blah' . $key }}" frameborder="0" allowfullscreen
                                    src="<?php
                                    if (isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id])) {
                                        echo $logo . '/' . $employeedoc[$document->id];
                                    }
                                    ?>" width="100%" height="400">
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


<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
