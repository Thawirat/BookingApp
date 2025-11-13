<?php $__env->startSection('content'); ?>
    <div>
        <div class="col-md-10 content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>จัดการผู้ใช้</h2>
                <div class="d-flex align-items-center">
                    <form action="<?php echo e(route('manage_users.index')); ?>" method="GET" id="filterForm"
                        class="d-flex flex-wrap align-items-center gap-2">

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ค้นหาผู้ใช้"
                                class="form-control" style="width: 200px;">

                            <select name="role" id="roleSelect" class="form-select" style="width: 160px;">
                                <option value="">ทุกบทบาท</option>
                                <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>ผู้ดูแลระบบหลัก
                                </option>
                                <option value="sub-admin" <?php echo e(request('role') == 'sub-admin' ? 'selected' : ''); ?>>
                                    ผู้ดูแลอาคาร</option>
                                <option value="user" <?php echo e(request('role') == 'user' ? 'selected' : ''); ?>>ผู้ใช้ทั่วไป
                                </option>
                            </select>

                            <select name="status" id="statusSelect" class="form-select" style="width: 160px;">
                                <option value="">ทุกสถานะ</option>
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>รออนุมัติ
                                </option>
                                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>อนุมัติแล้ว
                                </option>
                                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>ไม่อนุมัติ
                                </option>
                            </select>

                            <button type="submit"
                                class="btn btn-primary px-4 d-flex align-items-center justify-content-center"
                                style="height: 38px;">
                                <i class="fas fa-search me-1"></i> ค้นหา
                            </button>

                            <button type="button" id="resetButton"
                                class="btn btn-danger text-white px-4 d-flex align-items-center justify-content-center"
                                style="height: 38px;">
                                รีเซ็ต
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            <?php echo $__env->make('components.manage-user-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>รายการผู้ใช้</h5>
                                </div>

                                <div class="card-body">
                                    <?php if(session('success')): ?>
                                        <div class="alert alert-success">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ลำดับที่</th>
                                                <th class="text-center">ชื่อ-นามสกุล</th>
                                                <th class="text-center">หน่วยงาน</th>
                                                <th class="text-center">อีเมล</th>
                                                <th class="text-center">เบอร์โทรศัพท์</th>
                                                <th class="text-center">บทบาท</th>
                                                <th class="text-center">สถานะ</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo e(($users->currentPage() - 1) * $users->perPage() + $loop->iteration); ?>

                                                    </td>
                                                    <td class="text-center"><?php echo e($user->name); ?></td>
                                                    <td class="text-center"><?php echo e($user->department); ?></td>
                                                    <td class="text-center"><?php echo e($user->email); ?></td>
                                                    <td class="text-center"><?php echo e($user->phone_number); ?></td>
                                                    <td class="text-center">
                                                        <?php if($user->role === 'admin'): ?>
                                                            <span class="badge bg-primary">ผู้ดูแลระบบหลัก</span>
                                                        <?php elseif($user->role === 'sub-admin'): ?>
                                                            <span class="badge bg-info text-white">ผู้ดูแลอาคาร</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">ผู้ใช้ทั่วไป</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <?php echo $__env->make('components.dropdown.user-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                onclick="openEditUserModal(<?php echo e($user->id); ?>)">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <?php echo $__env->make('components.user-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-user-btn"
                                                                data-id="<?php echo e($user->id); ?>"
                                                                data-name="<?php echo e($user->name); ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                        <form id="deleteUserForm<?php echo e($user->id); ?>"
                                                            action="<?php echo e(route('manage_users.destroy', $user->id)); ?>"
                                                            method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-4">
                                        <?php echo e($users->appends(['search' => request('search')])->links('pagination::bootstrap-5')); ?>

                                    </div>
                                </div>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .content {
        margin: 0 auto;
    }

    :root {
        --filter-height: 42px;
    }

    /* ให้ทุกชิ้นมีขนาดและการจัดวางเหมือนกัน */
    #filterForm .filter-item {
        height: var(--filter-height);
        font-size: 14px;
        line-height: 1;
        display: flex;
        align-items: center;
    }

    /* ให้ช่อง input และ select มีขนาดใกล้เคียงกัน */
    #filterForm .form-control,
    #filterForm .form-select {
        height: var(--filter-height) !important;
        min-width: 180px;
    }

    /* ให้ปุ่มเท่ากันและอยู่ระดับเดียว */
    #filterForm .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        padding: 0 16px;
        font-size: 14px;
        height: var(--filter-height);
    }

    /* ปรับให้ wrap เมื่อจอเล็ก */
    @media (max-width: 768px) {
        #filterForm {
            flex-wrap: wrap;
        }

        #filterForm .filter-item {
            flex: 1 1 100%;
        }
    }
</style>
<?php $__env->startPush('scripts'); ?>
    <script type="text/javascript">
        window.openEditUserModal = function(userId) {
            fetch(`/api/users/${userId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(user => {
                    document.getElementById('editUserForm').action = `/manage-users/${userId}`;
                    document.getElementById('edit_name').value = user.name;
                    document.getElementById('edit_email').value = user.email;
                    document.getElementById('edit_role').value = user.role;
                    document.getElementById('edit_password').value = '';

                    // Trigger toggle after setting role
                    toggleBuildingsField();

                    // Load buildings
                    loadUserBuildings(userId);

                    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user data: ' + error.message);
                });
        };

        function loadUserBuildings(userId) {
            fetch(`/api/users/${userId}/buildings`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('buildings_container');
                    container.innerHTML = data.buildings.map(building => `
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           name="buildings[]"
                           value="${building.id}"
                           id="building_${building.id}"
                           ${building.assigned ? 'checked' : ''}>
                    <label class="form-check-label" for="building_${building.id}">
                        ${building.building_name}
                    </label>
                </div>
            `).join('');
                });
        }

        function toggleBuildingsField() {
            const roleSelect = document.getElementById('edit_role');
            const buildingsContainer = document.getElementById('buildings_container');
            const buildingsWrapper = buildingsContainer.closest('.mb-3');
            const selectedRole = roleSelect.value;

            // แสดงเฉพาะเมื่อเป็น sub-admin เท่านั้น
            if (selectedRole === 'sub-admin') {
                buildingsWrapper.style.display = 'block';
            } else {
                buildingsWrapper.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('edit_role');
            if (roleSelect) {
                roleSelect.addEventListener('change', toggleBuildingsField);
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-user-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-name');

                    Swal.fire({
                        title: 'ยืนยันการลบ',
                        html: `คุณแน่ใจหรือไม่ว่าต้องการลบ <strong>${userName}</strong>?`, // ใช้ html: แทน text:
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'ลบ',
                        cancelButtonText: 'ยกเลิก',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`deleteUserForm${userId}`).submit();
                        }
                    });
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('roleSelect').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
            document.getElementById('statusSelect').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });
        document.getElementById('resetButton').addEventListener('click', () => {
            window.location.href = "<?php echo e(route('manage_users.index')); ?>";
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/manage_users.blade.php ENDPATH**/ ?>