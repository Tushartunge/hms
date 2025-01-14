<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // Get all permissions
    public function index()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    // Assign permissions to a role
    public function assignPermissions(Request $request, $roleId)
    {
        $validated = $request->validate([
            'permissions' => 'array|required',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($validated['permissions']);

        return response()->json([
            'message' => 'Permissions assigned successfully.',
            'role' => $role->load('permissions'),
        ]);
    }
}

