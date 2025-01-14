<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create the Super Admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);

        // Create additional roles
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);
        $receptionRole = Role::firstOrCreate(['name' => 'reception']);

        // Define permissions
        $permissions = [
            'create_user',
            'view_user',
            'edit_user',
            'delete_user',
            'view_appointments',
            // Add other permissions as needed
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign all permissions to the Super Admin role
        $superAdminRole->syncPermissions(Permission::all());

        // Create a Super Admin user and assign the role
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin@123'), // Hash the password
            ]
        );

        // Assign the Super Admin role to the user
        $superAdmin->assignRole('super_admin');
    }
}
