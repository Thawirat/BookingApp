<!-- resources/views/booking.blade.php -->


<?php $__env->startSection('content'); ?>
    <div class="container">

        <div class="row mb-4">
            <div
                class="bg-gradient-to-r from-pink-500 via-blue-400 to-green-500 text-white p-8 rounded-lg shadow-lg mb-10 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">ระบบจองห้องออนไลน์</h1>
                <h2 class="text-xl md:text-2xl mt-2">มหาวิทยาลัยราชภัฏสกลนคร</h2>
                <p class="mt-4 text-lg opacity-90">บริการจองห้องเรียน ห้องประชุม และสถานที่จัดกิจกรรมต่างๆ แบบออนไลน์</p>
            </div>
            
            <div class=" p-4 rounded mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold">ประเภทห้อง</h3>
                </div>
                <div class="row g-3">
                    <?php
                        use App\Models\RoomType;
                        $roomTypes = RoomType::all();
                    ?>

                    <?php if($roomTypes->count()): ?>
                        <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-2">
                                <a href="<?php echo e(route('rooms.byType', ['type' => $type->id])); ?>" class="text-decoration-none">
                                    <div class="card text-center py-3 shadow-sm hover:shadow-lg transition">
                                        <div class="card-body">
                                            <i
                                                class="fas fa-<?php echo e($type->icon ?? 'building'); ?> text-warning display-6 mb-2"></i>
                                            <p class="mb-0 fw-semibold"><?php echo e($type->name); ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted">ไม่มีประเภทห้องที่จะแสดง</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class=" p-4 rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold">อาคารทั้งหมด</h3>
                </div>
                <div class="row g-3">
                    <?php $__currentLoopData = $buildings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $building): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-2">
                            <a href="<?php echo e(route('rooms.byBuilding', ['building_id' => $building->id])); ?>"
                                class="text-decoration-none">
                                <div class="card text-center py-3 shadow-sm hover:shadow-lg transition">
                                    <div class="card-body">
                                        <i class="fas fa-building text-primary display-6 mb-2"></i>
                                        <p class="mb-0 fw-semibold"><?php echo e($building->building_name); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                <h3 class="fw-bold">ห้องทั้งหมด</h3>
                <a href="<?php echo e(route('rooms.index')); ?>" class="text-warning fw-bold">ดูทั้งหมด</a>
            </div>
            <div class="grid grid-cols-4 gap-4 pb-3">
                <?php $__currentLoopData = $rooms->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('components.room-card', ['room' => $room], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/booking.blade.php ENDPATH**/ ?>