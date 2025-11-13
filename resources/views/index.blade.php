@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div>
                @include('components.banner')
            </div>
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card: อาคาร -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-blue-500 text-5xl mb-4">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="fw-bold">{{ $totalBuildings }} อาคาร</h3>
                    <p class="mt-2">อาคารที่ให้บริการจองห้อง</p>
                </div>
                <!-- Card: ห้อง -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-green-500 text-5xl mb-4">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <h3 class="fw-bold">{{ $totalRooms }} ห้อง</h3>
                    <p class="mt-2">ห้องที่เปิดให้จอง</p>
                </div>

                @php
                    $role = Auth::user()->getRoleNames()->first();
                @endphp

                @if ($role === 'admin')
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-yellow-500 text-5xl mb-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="fw-bold">
                            <span id="totalBookingsCount">{{ $totalBookings }}</span> การจองทั้งหมด
                        </h3>
                        <p class="mt-2">การจองทั้งหมดที่มีในระบบ</p>
                    </div>
                @elseif ($role === 'sub-admin')
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-sky-500 text-5xl mb-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="fw-bold">
                            <span id="totalbuildingBookingsCount">{{ $totalbuildingBookings }}</span> การจองในห้องทั้งหมด
                        </h3>
                        <p class="mt-2">การจองทั้งหมดในห้องที่อยู่ในการดูแล</p>
                    </div>
                @endif


                <!-- Card: Dashboard ผู้ดูแล -->
                @if (Auth::check() && Auth::user()->isAdminOrSubAdmin())
                    @php
                        $role = Auth::user()->getRoleNames()->first();
                        $roleDisplay =
                            [
                                'admin' => 'ผู้ดูแลระบบ',
                                'sub-admin' => 'ผู้ดูแลอาคาร',
                            ][$role] ?? $role;
                    @endphp
                    <a href="{{ route('dashboard') }}" class="block" style="text-decoration: none;">
                        <div
                            class="bg-white border border-blue-500 rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-shield text-blue-500 text-5xl mb-4"></i>
                                <h3 class="text-2xl fw-bold text-gray-800">สำหรับ {{ $roleDisplay }}</h3>
                                <p class="text-gray-500 mt-2">จัดการระบบสำหรับ {{ $roleDisplay }}</p>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
            <!-- การจองของฉัน -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-3">การจองของฉัน {{ $totalmyBookings }} รายการ</h3>
                    <a href="{{ route('my-bookings') }}" class="custom-link">
                        ดูรายการจองทั้งหมด
                    </a>
                </div>
                @if (isset($myBookings) && $myBookings->count() > 0)
                    <div class="booking-carousel d-flex overflow-auto pb-3">
                        @foreach ($myBookings as $booking)
                            <div class="card me-3 flex-shrink-0" style="width: 300px;">
                                <div class="position-relative">
                                    @if (!empty($booking->room) && !empty($booking->room->image))
                                        <img src="{{ asset('storage/' . $booking->room->image) }}"
                                            alt="{{ $booking->room->room_name ?? 'Room Image' }}"
                                            class="img-fluid rounded-lg">
                                    @else
                                        <div class="bg-light rounded-lg d-flex align-items-center justify-content-center py-5"
                                            style="height: 180px;">
                                            <span class="text-muted"><i class="bi bi-image me-2"></i>ไม่มีรูปภาพ</span>
                                        </div>
                                    @endif
                                    <span
                                        class="position-absolute top-0 end-0 m-2 badge bg-{{ $booking->status->status_name === 'อนุมัติแล้ว' ? 'success' : ($booking->status->status_name === 'รอดำเนินการ' ? 'warning text-dark' : 'secondary') }}">
                                        {{ $booking->status->status_name ?? '-' }}
                                    </span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="ps-3 pe-3 pt-3 pb-3">
                                        <h5 class="fw-bold text-dark">{{ $booking->room_name ?? '-' }}</h5>
                                        <p class="card-text text-muted mb-1">
                                        <div><strong>จองเมื่อ:</strong>
                                            {{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y') }}
                                        </div>
                                        <div><strong>อาคาร:</strong> {{ $booking->building_name ?? '-' }} </div>
                                        <div><strong>เริ่มวันที่:</strong>
                                            {{ \Carbon\Carbon::parse($booking->booking_start)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                            น.</div>
                                        <div><strong>ถึงวันที่:</strong>
                                            {{ \Carbon\Carbon::parse($booking->booking_end)->addYears(543)->format('d/m/Y เวลา H:i') }}
                                            น.</div>
                                        </p>
                                        <div class="mt-3 d-flex justify-content-center">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#detailsModal{{ $booking->id }}">
                                                <i class="fas fa-eye"></i> ดูรายละเอียดเพิ่มเติม
                                            </button>
                                        </div>
                                        @include('booking-status.modal')
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">คุณยังไม่มีการจองห้อง</p>
                @endif
            </div>
            <!-- Featured Rooms -->
            <h3 class="fw-bold mb-3">ห้องแนะนำ</h3>
            @if (isset($featuredRooms) && $featuredRooms->count() > 0)
                <div class="featured-carousel d-flex overflow-auto pb-3">
                    @foreach ($featuredRooms as $room)
                        @include('components.room-card', ['room' => $room])
                    @endforeach
                </div>
            @else
                <p class="text-muted">ไม่มีห้องแนะนำในขณะนี้</p>
            @endif
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
                            {{-- <p><i class="fas fa-envelope me-2 ms-1 text-warning"></i>booking@snru.ac.th</p> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-6" x-data="{ openFaq: null }">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">คำถามที่พบบ่อย (FAQ)</h4>
                        </div>
                        <div class="card-body">
                            @include('components.qna')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3 id="bookingCount" class="fw-bold">{{ $totalBookings }} การจองทั้งหมด</h3>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                let channelName =
                    "{{ $role === 'admin' ? 'bookings' : 'building-bookings.' . auth()->user()->building_id }}";

                console.log('Connecting to channel:', channelName);

                window.Echo.channel(channelName)
                    .listen('.new-booking', (event) => {
                        console.log('📢 New booking:', event.booking);

                        if ("{{ $role }}" === 'admin') {
                            let el = document.getElementById('totalBookingsCount');
                            if (el) el.textContent = parseInt(el.textContent) + 1;
                        }

                        if ("{{ $role }}" === 'sub-admin') {
                            let el = document.getElementById('totalbuildingBookingsCount');
                            if (el) el.textContent = parseInt(el.textContent) + 1;
                        }
                    });
            });
        </script>
    @endpush

@endsection
