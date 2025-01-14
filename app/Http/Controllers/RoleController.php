<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Get all roles
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    // Create a new role
    public function store(Request $request)
{
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string',
        'permissions' => 'required|array',
        'permissions.*' => 'exists:permissions,id'
    ]);

    // Create a new role
    $role = Role::create(['name' => $validated['name']]);

    // Attach permissions to the role
    $role->permissions()->attach($validated['permissions']);

    return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
}

    // Delete a role
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully.']);
    }
}

