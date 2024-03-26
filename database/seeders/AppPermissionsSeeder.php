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

        // Seed Root Offices
        $office1 = Office::factory()->create([
            'office_name' => 'Office of the Campus Director',
            'reports_to_office' => 1,
            'office_head_title' => 'Campus Director',
        ]);

        $office2 = Office::factory()->create([
            'office_name' => 'Curriculum and Instruction Division',
            'reports_to_office' => 1,
            'office_head_title' => 'CID Chief'
        ]);

        $office3 = Office::factory()->create([
            'office_name' => 'Student Services Divison',
            'reports_to_office' => 1,
            'office_head_title' => 'SSD Chief'
        ]);

        $office4 = Office::factory()->create([
            'office_name' => 'Computer Science Unit',
            'reports_to_office' => 2,
            'office_head_title' => 'Unit Head'
        ]);

        $office5 = Office::factory()->create([
            'office_name' => 'Engineering and Technology Unit',
            'reports_to_office' => 2,
            'office_head_title' => 'Unit Head'
        ]);

        $office6 = Office::factory()->create([
            'office_name' => 'Finance and Administrative Division',
            'reports_to_office' => 1,
            'office_head_title' => 'FAD Chief'
        ]);

        $office7 = Office::factory()->create([
            'office_name' => 'Information Technology Unit',
            'reports_to_office' => 6,
            'office_head_title' => 'IT Head'
        ]);

        // Seed User Accounts
        $user1 = User::factory()->create([
            'name_family' => 'Mouse',
            'name_first' => 'Mickey',
            'email' => 'mickey@mail.com',
            'office_id' => 7,
            'password' => Hash::make('abc.123')
        ]);
        $user2 = User::factory()->create([
            'name_family' => 'Robles',
            'name_first' => 'Princess',
            'email' => 'princess@mail.com',
            'office_id' => 1,
            'password' => Hash::make('abc.123')
        ]);

        $user3 = User::factory()->create([
            'name_family' => 'Diaz',
            'name_first' => 'Theresa Anne',
            'email' => 'theresa@mail.com',
            'office_id' => 1,
            'password' => Hash::make('abc.123')
        ]);

        $user4 = User::factory()->create([
            'name_family' => 'Coronel',
            'name_first' => 'Renz',
            'email' => 'renz@mail.com',
            'office_id' => 2,
            'password' => Hash::make('abc.123')
        ]);

        $user5 = User::factory()->create([
            'name_family' => 'Morante',
            'name_first' => 'Karizz Anne',
            'email' => 'karizz@mail.com',
            'office_id' => 2,
            'password' => Hash::make('abc.123')
        ]);

        $user6 = User::factory()->create([
            'name_family' => 'Ibanez',
            'name_first' => 'Jan Michael',
            'email' => 'jm@mail.com',
            'office_id' => 4,
            'password' => Hash::make('abc.123')
        ]);

        $user7 = User::factory()->create([
            'name_family' => 'Cruz',
            'name_first' => 'Mark Paolo',
            'email' => 'mark@mail.com',
            'office_id' => 4,
            'password' => Hash::make('abc.123')
        ]);

        $user8 = User::factory()->create([
            'name_family' => 'Quioc',
            'name_first' => 'Mary Anne',
            'email' => 'maryanne@mail.com',
            'office_id' => 4,
            'password' => Hash::make('abc.123')
        ]);

        $user9 = User::factory()->create([
            'name_family' => 'Tibay',
            'name_first' => 'Jona',
            'email' => 'jona@mail.com',
            'office_id' => 4,
            'password' => Hash::make('abc.123')
        ]);

        $user1->assignRole($roleAdmin);
        $user2->assignRole($roleStandard);
        $user3->assignRole($roleStandard);
        $user4->assignRole($roleStandard);
        $user5->assignRole($roleStandard);
        $user6->assignRole($roleStandard);
        $user7->assignRole($roleStandard);
        $user8->assignRole($roleStandard);
        $user9->assignRole($roleStandard);
    }
}
