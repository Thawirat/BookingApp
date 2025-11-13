<div class="card rounded-lg border-0 mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <h4 class="mb-0 fw-bold">รายละเอียดสถานที่/ห้อง/อาคาร</h4>
    </div>
    <div class="card-body p-4">
        <!-- Room Image -->
        <div class="mb-4">
            <?php if(isset($room->image)): ?>
                <img src="<?php echo e(asset('storage/' .$room->image)); ?>" alt="<?php echo e($room->room_name); ?>" class="img-fluid rounded-lg">
            <?php else: ?>
                <div class="bg-light rounded-lg d-flex align-items-center justify-content-center py-5">
                    <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                </div>
            <?php endif; ?>
        </div>
        <!-- Room Details -->
        <div class="mb-3">
            <!-- Building -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">อาคาร:</span>
                <span><?php echo e($room->building->building_name ?? 'ไม่ระบุ'); ?></span>
            </div>
            <!-- Room Name -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ชื่อห้อง:</span>
                <span class="fw-bold"><?php echo e($room->room_name); ?></span>
            </div>
            <!-- Floor -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ชั้น:</span>
                <span><?php echo e($room->class); ?></span>
            </div>
            <!-- Capacity -->
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted">ความจุ:</span>
                <span><?php echo e($room->capacity ?? '-'); ?> คน</span>
            </div>
            <!-- Details -->
            <div class="py-2">
                <div class="text-muted mb-1">รายละเอียด:</div>
                <p class="mb-0"><?php echo e($room->room_details); ?></p>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/partials/room-info.blade.php ENDPATH**/ ?>