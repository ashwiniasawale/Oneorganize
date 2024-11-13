<!-- resources/views/emails/hello_markdown.blade.php -->

<?php $__env->startComponent('mail::message'); ?>
# Hello

<?php echo e($content); ?>


Thanks,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?><?php /**PATH D:\xampp\htdocs\oneorganize\resources\views/email/task_mail.blade.php ENDPATH**/ ?>