<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ManageUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // 🔍 ค้นหาจากชื่อ อีเมล หรือหน่วยงาน
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // 🧩 กรองตามบทบาท (role)
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // 🧩 กรองตามสถานะ (status)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🧮 เรียงลำดับ: รออนุมัติ → ไม่ active → active
        $query->orderByRaw("CASE
        WHEN status = 'pending' THEN 1
        WHEN status = 'inactive' THEN 2
        ELSE 3 END")
            ->orderBy('created_at', 'desc');

        // 📄 แบ่งหน้า (15 ต่อหน้า) + คงค่าฟิลเตอร์
        $users = $query->paginate(20)->withQueryString();

        // 📊 สถิติผู้ใช้
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->where('status', 'active')->count();
        $subAdminCount = User::where('role', 'sub-admin')->where('status', 'active')->count();
        $regularUserCount = User::where('role', 'user')->where('status', 'active')->count();
        $statusPendingCount = User::where('status', 'pending')->count();
        $statusActiveCount = User::where('status', 'active')->count();
        $statusRejectedCount = User::where('status', 'rejected')->count();

        return view('dashboard.manage_users', compact(
            'users',
            'totalUsers',
            'adminCount',
            'regularUserCount',
            'subAdminCount',
            'statusPendingCount',
            'statusActiveCount',
            'statusRejectedCount'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,sub-admin,user',
            'password' => 'nullable|min:8',
            'buildings' => 'array',
        ]);

        $user = User::findOrFail($id);

        // ดึง Role Model ที่ตรงกับชื่อ role ที่เลือก
        $roleModel = Role::where('name', $request->role)->first();

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'role_id' => $roleModel ? $roleModel->id : null, // เพิ่มตรงนี้
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // syncRoles เพื่ออัปเดตตาราง model_has_roles ด้วย
        if ($roleModel) {
            $user->syncRoles([$roleModel->name]);
        }

        // Only sync buildings if the user is a sub-admin
        if ($request->role === 'sub-admin') {
            if ($request->has('buildings')) {
                $user->buildings()->sync($request->buildings);
            } else {
                $user->buildings()->detach();
            }
        }

        return redirect()->route('manage_users.index')
            ->with('success', 'User updated successfully');
    }

    public function getUserBuildings($id)
    {
        $user = User::findOrFail($id);
        $userBuildingIds = $user->buildings->pluck('id')->toArray();

        $buildings = Building::all()->map(function ($building) use ($userBuildingIds) {
            return [
                'id' => $building->id,
                'building_name' => $building->building_name,
                'assigned' => in_array($building->id, $userBuildingIds),
            ];
        });

        return response()->json(['buildings' => $buildings]);
    }
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        try {
            $user->delete();

            return redirect()->route('manage_users.index')
                ->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->route('manage_users.index')
                ->with('error', 'ไม่สามารถลบผู้ใช้ได้: ' . $e->getMessage());
        }
    }
}
