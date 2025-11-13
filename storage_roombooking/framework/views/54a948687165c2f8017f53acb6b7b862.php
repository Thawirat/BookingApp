<div class="d-flex flex-column align-items-center gap-2">
    
    <span
        class="badge
            <?php if($booking->status_id == 3): ?> bg-warning
            <?php elseif($booking->status_id == 4): ?> bg-success
            <?php elseif($booking->status_id == 5): ?> bg-danger
            <?php else: ?> bg-secondary <?php endif; ?>"
        data-bs-toggle="tooltip" data-bs-placement="top"
        title="อนุมัติโดย: <?php echo e($booking->approver_name ?? 'ยังไม่มีผู้อนุมัติ'); ?> <?php echo e($booking->approver_position ? 'ตำแหน่ง: ' . $booking->approver_position : ''); ?>">
        <?php echo e($booking->status->status_name ?? '-'); ?>

    </span>

    
    <div class="dropdown">
        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fas fa-edit"></i> เปลี่ยนสถานะ
        </button>
        <ul class="dropdown-menu">
            <?php $__currentLoopData = \App\Enums\Bookingstatus::options(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <form action="<?php echo e(route('booking.update-status', $booking->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="status_id" value="<?php echo e($status); ?>">
                        <button type="submit" class="dropdown-item <?php echo e($info['class']); ?>">
                            <i class="<?php echo e($info['icon']); ?>"></i>
                            <?php echo e($info['label']); ?>

                        </button>
                    </form>
                </li>
                <?php if($loop->iteration === 2): ?>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/component-dropdown/accept.blade.php ENDPATH**/ ?>