<?php $__env->startSection('title', __('general.pending_vehicles')); ?>
<?php $__env->startSection('page-title', __('general.pending_vehicles')); ?>

<?php $__env->startSection('content'); ?>
<div class="mt-6 box box--stacked p-6"><?php echo e(__('Pending vehicle verification view is now available.')); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/admin/vehicles/pending.blade.php ENDPATH**/ ?>