<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdminUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        $admins = User::role('Admin')->orderBy('name')->paginate(20);

        return view('admin_users.index', compact('admins'));
    }

    public function store(StoreAdminUserRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $adminRole = Role::firstOrCreate(['name' => 'Admin'], ['guard_name' => 'web']);

                if ($adminRole->permissions()->count() === 0) {
                    $adminRole->syncPermissions(
                        Permission::whereIn('name', [
                            'input-absensi',
                            'input-bibit',
                            'view-any-laporan',
                            'manage-master-data',
                        ])->get()
                    );
                }

                $admin = User::create($request->validated());
                $admin->assignRole($adminRole);
            });

            return redirect()->route('admin-users.index')->with('success', 'Admin berhasil ditambahkan.');
        } catch (\Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan admin: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->route('admin-users.index')->with('error', 'Akun yang sedang login tidak bisa dihapus.');
        }

        if (!$user->hasRole('Admin') || $user->hasRole('Owner')) {
            return redirect()->route('admin-users.index')->with('error', 'Hanya akun Admin yang bisa dihapus.');
        }

        try {
            DB::transaction(function () use ($user) {
                $user->syncPermissions([]);
                $user->syncRoles([]);
                $user->delete();
            });

            return redirect()->route('admin-users.index')->with('success', 'Admin berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('admin-users.index')->with('error', 'Terjadi kesalahan saat menghapus admin: ' . $e->getMessage());
        }
    }
}
