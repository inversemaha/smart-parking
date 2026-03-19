<?php $__env->startSection('title', __('general.roles')); ?>
<?php $__env->startSection('page-title', __('general.roles')); ?>

<?php
$breadcrumb = [
    ['title' => __('general.admin'), 'url' => route('admin.dashboard.index')],
    ['title' => __('general.permissions'), 'url' => route('admin.permissions.index')],
    ['title' => __('general.roles')]
];
?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-12 gap-6">
    <!-- Roles Management -->
    <div class="col-span-12 xl:col-span-8">
        <div class="box p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium"><?php echo e(__('general.roles')); ?></h3>
                <button type="button" class="btn btn-primary" onclick="openCreateRoleModal()">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    <?php echo e(__('general.create_role')); ?>

                </button>
            </div>

            <div class="space-y-4">
                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-slate-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="font-medium text-lg"><?php echo e($role->name); ?></h4>
                                    <span class="badge badge-secondary"><?php echo e($role->users->count()); ?> <?php echo e(__('general.users')); ?></span>
                                </div>
                                <?php if($role->description): ?>
                                    <p class="text-slate-600 mb-3"><?php echo e($role->description); ?></p>
                                <?php endif; ?>
                                <div class="flex flex-wrap gap-1">
                                    <?php $__currentLoopData = $role->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge badge-outline-primary"><?php echo e($permission->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="editRole(<?php echo e($role->id); ?>)">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <?php if($role->name !== 'super-admin'): ?>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteRole(<?php echo e($role->id); ?>)">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8 text-slate-500">
                        <?php echo e(__('general.no_roles_found')); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-span-12 xl:col-span-4">
        <div class="box p-6">
            <h3 class="text-lg font-medium mb-6"><?php echo e(__('general.role_statistics')); ?></h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <span class="text-blue-700"><?php echo e(__('general.total_roles')); ?></span>
                    <span class="font-medium text-blue-800"><?php echo e($roles->count()); ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <span class="text-green-700"><?php echo e(__('general.total_permissions')); ?></span>
                    <span class="font-medium text-green-800"><?php echo e($permissions->count()); ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <span class="text-purple-700"><?php echo e(__('general.active_users_with_roles')); ?></span>
                    <span class="font-medium text-purple-800"><?php echo e($roles->sum(fn($r) => $r->users->count())); ?></span>
                </div>
            </div>
        </div>

        <!-- Permission Quick Reference -->
        <div class="box p-6 mt-6">
            <h3 class="text-lg font-medium mb-6"><?php echo e(__('general.permission_modules')); ?></h3>
            <div class="space-y-2">
                <?php
                    $modules = $permissions->groupBy('module');
                ?>
                <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-2 hover:bg-slate-50 rounded">
                        <span class="text-slate-700 capitalize"><?php echo e($module); ?></span>
                        <span class="badge badge-outline-secondary"><?php echo e($modulePermissions->count()); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.roles.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto"><?php echo e(__('general.create_role')); ?></h2>
                    <button type="button" class="btn btn-rounded-secondary hidden sm:flex p-1" data-tw-dismiss="modal">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-12">
                        <label for="role_name" class="form-label"><?php echo e(__('general.role_name')); ?></label>
                        <input type="text" id="role_name" name="name" class="form-control" placeholder="<?php echo e(__('general.enter_role_name')); ?>" required>
                    </div>
                    <div class="col-span-12">
                        <label for="role_description" class="form-label"><?php echo e(__('general.description')); ?></label>
                        <textarea id="role_description" name="description" class="form-control" placeholder="<?php echo e(__('general.enter_description')); ?>"></textarea>
                    </div>
                    <div class="col-span-12">
                        <label class="form-label"><?php echo e(__('general.permissions')); ?></label>
                        <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border p-3 rounded">
                            <?php $__currentLoopData = $permissions->groupBy('module'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-span-2 border-b pb-2 mb-2">
                                    <h5 class="font-medium capitalize"><?php echo e($module); ?></h5>
                                </div>
                                <?php $__currentLoopData = $modulePermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="perm_<?php echo e($permission->id); ?>" name="permissions[]" value="<?php echo e($permission->id); ?>" class="form-check">
                                        <label for="perm_<?php echo e($permission->id); ?>" class="ml-2 text-sm"><?php echo e($permission->name); ?></label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary w-20 mr-1" data-tw-dismiss="modal"><?php echo e(__('general.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary w-20"><?php echo e(__('general.create')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div id="editRoleForm">
                <!-- Dynamic content loaded here -->
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openCreateRoleModal() {
    const modal = new bootstrap.Modal(document.getElementById('createRoleModal'));
    modal.show();
}

function editRole(id) {
    // Load role data and show edit modal
    fetch(`/admin/roles/${id}/edit`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('editRoleForm').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            modal.show();
        });
}

function deleteRole(id) {
    if (confirm('<?php echo e(__("general.confirm_delete_role")); ?>')) {
        // Implement delete functionality
        console.log('Delete role:', id);
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /media/bot/7E246BE4246B9DC1/laragon/www/parking/resources/views/admin/roles/index.blade.php ENDPATH**/ ?>