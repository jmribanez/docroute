<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AppPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // USER MANAGEMENT
        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'reset password']);

        // DOCUMENT MANAGEMENT
        Permission::create(['name' => 'list all documents']);
        Permission::create(['name' => 'list user documents']);
        Permission::create(['name' => 'create document']);
        Permission::create(['name' => 'view document']);
        Permission::create(['name' => 'update document']);
        Permission::create(['name' => 'delete document']);

        // create roles
        $roleAdmin = Role::create(['name' => 'Administrator'])
            ->givePermissionTo('list users', 'create user', 'view user', 'edit user', 'update user', 'delete user', 'reset password', 
            'list all documents', 'list user documents', 'create document', 'view document', 'update document');
        $roleStandard = Role::create(['name' => 'Standard'])
            ->givePermissionTo(['list user documents', 'create document', 'view document', 'update document']);

        // Seed Root Office
        $office1 = Office::factory()->create([
            'office_name' => 'Office of the Campus Director',
            'reports_to_office' => 1,
            'office_head_title' => 'Campus Director',
        ]);

        // Seed User Accounts
        $user1 = User::factory()->create([
            'name_family' => 'De Santa',
            'name_first' => 'Michael',
            'email' => 'michael@mail.com',
            'office_id' => 1,
            'password' => Hash::make('wordscapes.123')
        ]);
        $user2 = User::factory()->create([
            'name_family' => 'Clinton',
            'name_first' => 'Franklin',
            'email' => 'franklin@mail.com',
            'password' => Hash::make('wordscapes.123')
        ]);

        $user1->assignRole($roleAdmin);
        $user2->assignRole($roleStandard);
    }
}
