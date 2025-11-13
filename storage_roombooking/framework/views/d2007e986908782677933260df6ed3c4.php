<?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm">
            <div class="position-relative">
                <img alt="ภาพ<?php echo e($building->building_name); ?>" class="card-img-top"
                    src="<?php echo e($building->image ? asset('storage/' . $building->image) : asset('images/no-picture.jpg')); ?>"
                    style="height: 180px; object-fit: cover;" />
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-success">
                        <i class="fas fa-door-open me-1"></i><?php echo e($building->rooms->count()); ?> ห้อง
                    </span>
                </div>
            </div>
            <div class="card-body px-3 py-2"> 
                <h5 class="card-title"><?php echo e($building->building_name); ?></h5>
                <p class="card-text text-muted small mb-2">
                    <i class="fas fa-user-edit me-1"></i>บันทึกโดย:
                    <?php echo e($building->citizen_save); ?>

                </p>
                <div class="btn-group w-100" role="group">
                    <?php if(Auth::user()->role === 'admin'): ?>
                        <button class="btn btn-sm btn-warning flex-grow-1"
                            onclick="openEditBuildingModal('<?php echo e($building->id); ?>', '<?php echo e($building->building_name); ?>', '<?php echo e($building->citizen_save); ?>')">
                            <i class="fas fa-edit me-1"></i>
                        </button>
                        <button class="btn btn-sm btn-danger flex-grow-1"
                            onclick="confirmDeleteBuilding('<?php echo e($building->id); ?>', '<?php echo e($building->building_name); ?>')">
                            <i class="fas fa-trash me-1"></i>
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-info flex-grow-1"
                        onclick="window.location.href='<?php echo e(route('manage_rooms.show', $building->id)); ?>'">
                        <i class="fas fa-door-open me-1"></i> ดูห้อง
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH /var/www/html/resources/views/components/building-card.blade.php ENDPATH**/ ?>