<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = ['cashier', 'technician'];
        $features = RolePermission::$features;

        $permissions = [];
        foreach ($roles as $role) {
            $rows = RolePermission::where('role', $role)->pluck('enabled', 'feature');
            foreach ($features as $key => $label) {
                $permissions[$role][$key] = (bool) ($rows[$key] ?? false);
            }
        }

        return view('settings.permissions.index', compact('roles', 'features', 'permissions'));
    }

    public function update(Request $request)
    {
        $roles = ['cashier', 'technician'];
        $features = array_keys(RolePermission::$features);

        foreach ($roles as $role) {
            foreach ($features as $feature) {
                $enabled = $request->boolean("{$role}.{$feature}");
                RolePermission::updateOrCreate(
                    ['role' => $role, 'feature' => $feature],
                    ['enabled' => $enabled]
                );
            }
            RolePermission::clearCache($role);
        }

        return back()->with('success', 'Permissions updated successfully.');
    }
}
