<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row mb-4">
            <div>
                <?php echo $__env->make('components.banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card: อาคาร -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-blue-500 text-5xl mb-4">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="fw-bold"><?php echo e($totalBuildings); ?> อาคาร</h3>
                    <p class="mt-2">อาคารที่ให้บริการจองห้อง</p>
                </div>
                <!-- Card: ห้อง -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-green-500 text-5xl mb-4">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <h3 class="fw-bold"><?php echo e($totalRooms); ?> ห้อง</h3>
                    <p class="mt-2">ห้องที่เปิดให้จอง</p>
                </div>

                <?php
                    $role = Auth::user()->getRoleNames()->first();
                ?>

                <?php if($role === 'admin'): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-yellow-500 text-5xl mb-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="fw-bold">
                            <span id="totalBookingsCount"><?php echo e($totalBookings); ?></span> การจองทั้งหมด
                        </h3>
                        <p class="mt-2">การจองทั้งหมดที่มีในระบบ</p>
                    </div>
                <?php elseif($role === 'sub-admin'): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-sky-500 text-5xl mb-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="fw-bold">
                            <span id="totalbuildingBookingsCount"><?php echo e($totalbuildingBookings); ?></span> การจองในห้องทั้งหมด
                        </h3>
                        <p class="mt-2">การจองทั้งหมดในห้องที่อยู่ในการดูแล</p>
                    </div>
                <?php endif; ?>


                <!-- Card: Dashboard ผู้ดูแล -->
                <?php if(Auth::check() && Auth::user()->isAdminOrSubAdmin()): ?>
                    <?php
                        $role = Auth::user()->getRoleNames()->first();
                        $roleDisplay =
                            [
                                'admin' => 'ผู้ดูแลระบบ',
                                'sub-admin' => 'ผู้ดูแลอาคาร',
                            ][$role] ?? $role;
                    ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="block" style="text-decoration: none;">
                        <div
                            class="bg-white border border-blue-500 rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-shield text-blue-500 text-5xl mb-4"></i>
                                <h3 class="text-2xl fw-bold text-gray-800">สำหรับ <?php echo e($roleDisplay); ?></h3>
                                <p class="text-gray-500 mt-2">จัดการระบบสำหรับ <?php echo e($roleDisplay); ?></p>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
            <!-- การจองของฉัน -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-3">การจองของฉัน <?php echo e($totalmyBookings); ?> รายการ</h3>
                    <a href="<?php echo e(route('my-bookings')); ?>" class="custom-link">
                        ดูรายการจองทั้งหมด
                    </a>
                </div>
                <?php if(isset($myBookings) && $myBookings->count() > 0): ?>
                    <div class="booking-carousel d-flex overflow-auto pb-3">
                        <?php $__currentLoopData = $myBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card me-3 flex-shrink-0" style="width: 300px;">
                                <div class="position-relative">
                                    <?php if(!empty($booking->room) && !empty($booking->room->image)): ?>
                                        <img src="<?php echo e(asset('storage/' . $booking->room->image)); ?>"
                                            alt="<?php echo e($booking->room->room_name ?? 'Room Image'); ?>"
                                            class="img-fluid rounded-lg">
                                    <?php else: ?>
                                        <div class="bg-light rounded-lg d-flex align-items-center justify-content-center py-5"
                                            style="height: 180px;">
                                            <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                                        </div>
                                    <?php endif; ?>
                                    <span
                                        class="position-absolute top-0 end-0 m-2 badge bg-<?php echo e($booking->status->status_name === 'อนุมัติแล้ว' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : 'secondary')); ?>">
                                        <?php echo e($booking->status->status_name ?? '-'); ?>

                                    </span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="ps-3 pe-3 pt-3 pb-3">
                                        <h5 class="fw-bold text-dark"><?php echo e($booking->room_name ?? '-'); ?></h5>
                                        <p class="card-text text-muted mb-1">
                                        <div><strong>จองเมื่อ:</strong>
                                            <?php echo e(\Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y')); ?>

                                        </div>
                                        <div><strong>อาคาร:</strong> <?php echo e($booking->building_name ?? '-'); ?> </div>
                                        <div><strong>เริ่มวันที่:</strong>
                                            <?php echo e(\Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y เวลา H:i')); ?>

                                            น.</div>
                                        <div><strong>ถึงวันที่:</strong>
                                            <?php echo e(\Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y เวลา H:i')); ?>

                                            น.</div>
                                        </p>
                                        <div class="mt-3 d-flex justify-content-center">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo e($booking->id); ?>">
                                                <i class="fas fa-eye"></i> ดูรายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                        <?php echo $__env->make('booking-status.modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">คุณยังไม่มีการจองห้อง</p>
                <?php endif; ?>
            </div>
            <!-- Featured Rooms -->
            <h3 class="fw-bold mb-3">ห้องแนะนำ</h3>
            <?php if(isset($featuredRooms) && $featuredRooms->count() > 0): ?>
                <div class="featured-carousel d-flex overflow-auto pb-3">
                    <?php $__currentLoopData = $featuredRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('components.room-card', ['room' => $room], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-muted">ไม่มีห้องแนะนำในขณะนี้</p>
            <?php endif; ?>
            <!-- How to Book Section -->
            <div class="card bg-light mb-4">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4 text-center">วิธีการจองห้อง</h3>
                    <div class="row text-center g-4">
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-primary text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-search fa-2x"></i>
                                </div>
                                <h4>1. เลือกห้อง</h4>
                                <p>ค้นหาและเลือกห้องที่ต้องการจองตามวัตถุประสงค์การใช้งาน</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-success text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                                <h4>2. เลือกวันเวลา</h4>
                                <p>เลือกวันและเวลาที่ต้องการใช้งานห้อง</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-warning text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-clipboard-list fa-2x"></i>
                                </div>
                                <h4>3. กรอกข้อมูล</h4>
                                <p>กรอกข้อมูลการจองและรายละเอียดการใช้งาน</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3">
                                <div class="rounded-circle bg-danger text-white d-inline-flex justify-content-center align-items-center mb-3"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h4>4. รอการยืนยัน</h4>
                                <p>รอการยืนยันการจองผ่านอีเมลหรือตรวจสอบในระบบ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & FAQ -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">ติดต่อเรา</h4>
                        </div>
                        <div class="card-body ps-3">
                            <p><i class="fas fa-university me-2 ms-1 text-primary"></i>มหาวิทยาลัยราชภัฏสกลนคร</p>
                            <p><i class="fas fa-map-marker-alt me-2 ms-1 text-danger"></i>เลขที่ 680 ถนนนิตโย
                                ตำบลธาตุเชิงชุม อำเภอเมือง จังหวัดสกลนคร 47000</p>
                            <p><i class="fas fa-phone-alt me-2 ms-1 text-success"></i>โทรศัพท์ 042-970021 , 042-970094</p>
                            <p><i class="fas fa-fax me-2 ms-1 text-success"></i>โทรสาร 042-970022</p>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6" x-data="{ openFaq: null }">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">คำถามที่พบบ่อย (FAQ)</h4>
                        </div>
                        <div class="card-body">
                            <?php echo $__env->make('components.qna', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3 id="bookingCount" class="fw-bold"><?php echo e($totalBookings); ?> การจองทั้งหมด</h3>
    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let channelName =
                    "<?php echo e($role === 'admin' ? 'bookings' : 'building-bookings.' . auth()->user()->building_id); ?>";

                console.log('Connecting to channel:', channelName);

                window.Echo.channel(channelName)
                    .listen('.new-booking', (event) => {
                        console.log('📢 New booking:', event.booking);

                        if ("<?php echo e($role); ?>" === 'admin') {
                            let el = document.getElementById('totalBookingsCount');
                            if (el) el.textContent = parseInt(el.textContent) + 1;
                        }

                        if ("<?php echo e($role); ?>" === 'sub-admin') {
                            let el = document.getElementById('totalbuildingBookingsCount');
                            if (el) el.textContent = parseInt(el.textContent) + 1;
                        }
                    });
            });
        </script>
    <?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/index.blade.php ENDPATH**/ ?>