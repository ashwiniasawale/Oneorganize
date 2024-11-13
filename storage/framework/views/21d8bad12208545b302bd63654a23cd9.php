<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Bug Report')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.index')); ?>"><?php echo e(__('Project')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('projects.show',$project->id)); ?>">    <?php echo e(ucwords($project->project_name)); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Bug Report')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/dragula.min.css')); ?>" id="main-style-link">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>

    <script src="<?php echo e(asset('assets/js/plugins/dragula.min.js')); ?>"></script>
    <script>
        !function (a) {
            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {
                a('[data-plugin="dragula"]').each(function () {
                    var t = a(this).data("containers"), n = [];
                    if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function (a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function (el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id).each(function () {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('id');

                        var stage_id = $(target).attr('data-id');

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);

                        $.ajax({
                            url: '<?php echo e(route('bug.kanban.order')); ?>',
                            type: 'POST',
                            data: {bug_id: id, status_id: stage_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                                show_toastr('success', "Bug Moved Successfully.", 'success');
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                show_toastr('error', "something went wrong. ", 'error');
                            }
                        });

                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery), function (a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);
    </script>
    <script>
        $(document).on('click', '#form-comment button', function (e) {
            var comment = $.trim($("#form-comment textarea[name='comment']").val());
            var name = '<?php echo e(\Auth::user()->name); ?>';
            if (comment != '') {
                $.ajax({
                    url: $("#form-comment").data('action'),
                    data: {comment: comment, "_token": $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function (data) {
                        // data = JSON.parse(data);
                        // console.log()
// return false;
                        var html = "<li class='media mb-20'>" +
                            "                    <div class='media-body'>" +
                            "                    <div class='d-flex justify-content-between align-items-end'><div>" +
                            "                        <h5 class='mt-0'>" + name + "</h5>" +
                            "                        <p class='mb-0 text-xs'>" + data.data.comment + "</p></div>" +
                            "                           <div class='comment-trash' style=\"float: right\">" +
                            "                               <a href='#' class='btn btn-sm red btn-danger delete-comment' data-url='" + data.data.deleteUrl + "' >" +
                            "                                   <i class='ti ti-trash'></i>" +
                            "                               </a>" +
                            "                           </div>" +
                            "                           </div>" +
                            "                    </div>" +
                            "                </li>";

                        $("#comments").prepend(html);

                        $("#form-comment textarea[name='comment']").val('');
                        show_toastr('<?php echo e(__("success")); ?>', '<?php echo e(__("Comment Added Successfully!")); ?>', 'success');
                    },
                    error: function (data) {
                        show_toastr('<?php echo e(__("error")); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                    }
                });
            } else {
                show_toastr('<?php echo e(__("error")); ?>', '<?php echo e(__("Please write comment!")); ?>', 'error');
            }
        });

        $(document).on("click", ".delete-comment", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('<?php echo e(__("Success")); ?>', '<?php echo e(__("Comment Deleted Successfully!")); ?>', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('<?php echo e(__("Error")); ?>', data.message, 'error');
                        } else {
                            show_toastr('<?php echo e(__("Error")); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-file', function (e) {
            e.preventDefault();
            $.ajax({
                url: $("#form-file").data('url'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    show_toastr('<?php echo e(__("success")); ?>', '<?php echo e(__("File Added Successfully!")); ?>', 'success');
                    var delLink = '';

                    $('.file_update').html('');
                    $('#file-error').html('');

                    if (data.deleteUrl.length > 0) {
                        delLink = "<a href='#' class='text-danger text-muted delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                            "                                        <i class='dripicons-trash'></i>" +
                            "                                    </a>";
                    }

                    var html = '<div class="col-8 mb-2 file-' + data.id + '">' +
                        '                                    <h5 class="mt-0 mb-1 font-weight-bold text-sm"> ' + data.name + '</h5>' +
                        '                                    <p class="m-0 text-xs">' + data.file_size + '</p>' +
                        '                                </div>' +
                        '                                <div class="col-4 mb-2 file-' + data.id + '">' +
                        '                                    <div class="comment-trash" style="float: right">' +
                        '                                        <a download href="<?php echo e(asset(Storage::url('bugs'))); ?>/' + data.file + '" class="btn btn-sm btn-primary">' +
                        '                                            <i class="ti ti-download"></i>' +
                        '                                        </a>' +
                        '                                        <a href="#" class="btn btn-sm red btn-danger delete-comment-file m-0 px-2" data-id="' + data.id + '" data-url="' + data.deleteUrl + '">' +
                        '                                            <i class="ti ti-trash"></i>' +
                        '                                        </a>' +
                        '                                    </div>' +
                        '                                </div>';

                    $("#comments-file").prepend(html);
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        $('#file-error').text(data.errors.file[0]).show();
                    } else {
                        show_toastr('<?php echo e(__("error")); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                    }
                }
            });
        });

        $(document).on("click", ".delete-comment-file", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        show_toastr('<?php echo e(__("Success")); ?>', '<?php echo e(__("File Deleted Successfully!")); ?>', 'success');
                        $('.file-' + btn.attr('data-id')).remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            show_toastr('<?php echo e(__("Error")); ?>', data.message, 'error');
                        } else {
                            show_toastr('<?php echo e(__("Error")); ?>', '<?php echo e(__("Some Thing Is Wrong!")); ?>', 'error');
                        }
                    }
                });
            }
        });
    </script>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage bug report')): ?>
            <a href="<?php echo e(route('task.bug',$project->id)); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('List')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-list"></i>
            </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create bug report')): ?>
            <a href="#" data-size="lg" data-url="<?php echo e(route('task.bug.create',$project->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create New Bug')); ?>" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
        <a href="<?php echo e(route('projects.show',$project->id)); ?>" class="btn btn-primary btn-sm " data-bs-toggle="tooltip" title="" data-bs-original-title="Back">
               <span class="btn-inner--icon"><i class="ti ti-arrow-left"></i></span>
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $json = [];
        foreach ($bugStatus as $status){
            $json[] = 'task-list-'.$status->id;
        }
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='<?php echo e(json_encode($json)); ?>' data-plugin="dragula">
                <?php $__currentLoopData = $bugStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $bugs = $status->bugs($project->id) ?>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">

                                    <span class="btn btn-sm btn-primary btn-icon count">
                                        <?php echo e(count($bugs)); ?>

                                    </span>
                                </div>
                                <h4 class="mb-0"><?php echo e($status->title); ?></h4>
                            </div>
                            <div class="card-body kanban-box" id="task-list-<?php echo e($status->id); ?>" data-id="<?php echo e($status->id); ?>">
                                <?php $__currentLoopData = $bugs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bug): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="card draggable-item" id="<?php echo e($bug->id); ?>">
                                        <div class="pt-3 ps-3">
                                            <?php if($bug->priority =='low'): ?>
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-success"><?php echo e(ucfirst($bug->priority)); ?></span>
                                            <?php elseif($bug->priority =='medium'): ?>
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-warning"><?php echo e(ucfirst($bug->priority)); ?></span>
                                            <?php elseif($bug->priority =='high'): ?>
                                                <span class="p-2 px-3 rounded badge badge-pill badge-xs bg-danger"><?php echo e(ucfirst($bug->priority)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-header border-0 pb-0 position-relative">
                                            <h5>
                                                <a href="#" data-url="<?php echo e(route('task.bug.show',[$project->id,$bug->id])); ?>" data-ajax-popup="true" data-size="lg" data-bs-original-title="<?php echo e($bug->title); ?>"><?php echo e($bug->title); ?></a>
                                            </h5>
                                            <div class="card-header-right">
                                                <div class="btn-group card-option">
                                                    <button type="button" class="btn dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <?php if(Gate::check('edit bug report') || Gate::check('delete bug report')): ?>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit project task')): ?>
                                                                <a href="#!" data-size="lg" data-url="<?php echo e(route('task.bug.edit',[$project->id,$bug->id])); ?>" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="<?php echo e(__('Edit ').$bug->name); ?>">
                                                                    <i class="ti ti-pencil"></i>
                                                                    <span><?php echo e(__('Edit')); ?></span>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete project task')): ?>
                                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['task.bug.destroy', [$project->id,$bug->id]]]); ?>

                                                                <a href="#!" class="dropdown-item bs-pass-para">
                                                                    <i class="ti ti-archive"></i>
                                                                    <span> <?php echo e(__('Delete')); ?> </span>
                                                                </a>
                                                                <?php echo Form::close(); ?>

                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item d-inline-flex align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Start Date')); ?>">
                                                         <?php echo e(\Auth::user()->dateFormat($bug->start_date)); ?>

                                                    </li>

                                                </ul>
                                                <div class="user-group">
                                                    <span data-bs-toggle="tooltip" title="<?php echo e(__('End Date')); ?>">  <?php echo e(\Auth::user()->dateFormat($bug->due_date)); ?></span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <?php $user = $bug->users(); ?>

                                                <div class="user-group">
                                                    <img <?php if(isset($user[0]->avatar)): ?> src="<?php echo e(asset('/storage/uploads/avatar/'.$user[0]->avatar)); ?>" <?php else: ?> src="<?php echo e(asset('/storage/uploads/avatar/avatar.png')); ?>" <?php endif; ?> alt="image" data-bs-toggle="tooltip" title="<?php echo e((!empty($user[0])?$user[0]->name:'')); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/projects/bugKanban.blade.php ENDPATH**/ ?>