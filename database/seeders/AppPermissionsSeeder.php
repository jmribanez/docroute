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
            'office_name' => 'Office of the University President',
            'reports_to_office' => 1,
            'office_head' => 2,
            'office_head_title' => 'University President',
        ]);

        $office2 = Office::factory()->create([
            'office_name' => 'Office of the University and Board Secretary',
            'reports_to_office' => 1,
            'office_head_title' => 'University and Board Secretary'
        ]);

        $office3 = Office::factory()->create([
            'office_name' => 'Office of the Executive Vice President',
            'reports_to_office' => 1,
            'office_head' => 4,
            'office_head_title' => 'Executive Vice President'
        ]);

        $office4 = Office::factory()->create([
            'office_name' => 'Office of the Vice President for Administration and Finance',
            'reports_to_office' => 3,
            'office_head' => 4,
            'office_head_title' => 'Vice President for Administration and Finance'
        ]);

        $office5 = Office::factory()->create([
            'office_name' => 'Office of the Vice President for Academic Affairs',
            'reports_to_office' => 3,
            'office_head' => 4,
            'office_head_title' => 'Vice President for Academic Affairs'
        ]);

        $office6 = Office::factory()->create([
            'office_name' => 'College of Computing Studies',
            'reports_to_office' => 5,
            'office_head' => 9,
            'office_head_title' => 'Dean'
        ]);

        $office7 = Office::factory()->create([
            'office_name' => 'Office of the University Registrar',
            'reports_to_office' => 5,
            'office_head' => 10,
            'office_head_title' => 'University Registrar'
        ]);

        $office8 = Office::factory()->create([
            'office_name' => 'Office of the Vice President for Research, Innovation, Training and Extension',
            'reports_to_office' => 3,
            'office_head_title' => 'Vice President for Research, Innovation, Training and Extension'
        ]);

        $office9 = Office::factory()->create([
            'office_name' => 'Management Information Systems Office',
            'reports_to_office' => 8,
            'office_head' => 1,
            'office_head_title' => 'Director'
        ]);


        // Seed User Accounts

        $user1 = User::factory()->create([
            'name_family' => 'Quito',
            'name_first' => 'Angelito',
            'email' => 'angelito@mail.com',
            'office_id' => 9,
            'password' => Hash::make('abc.123')
        ]);
        $user2 = User::factory()->create([
            'name_family' => 'Baking',
            'name_first' => 'Enrique',
            'email' => 'enrique@mail.com',
            'office_id' => 1,
            'password' => Hash::make('abc.123')
        ]);

        $user3 = User::factory()->create([
            'name_family' => 'Robles',
            'name_first' => 'Princess',
            'email' => 'princess@mail.com',
            'office_id' => 1,
            'password' => Hash::make('abc.123')
        ]);

        $user4 = User::factory()->create([
            'name_family' => 'Hernandez',
            'name_first' => 'Reden',
            'email' => 'reden@mail.com',
            'office_id' => 5,
            'password' => Hash::make('abc.123')
        ]);

        $user5 = User::factory()->create([
            'name_family' => 'Abulencia',
            'name_first' => 'Erwinda',
            'email' => 'erwinda@mail.com',
            'office_id' => 2,
            'password' => Hash::make('abc.123')
        ]);

        $user6 = User::factory()->create([
            'name_family' => 'Manabat',
            'name_first' => 'Janina',
            'email' => 'janina@mail.com',
            'office_id' => 3,
            'password' => Hash::make('abc.123')
        ]);

        $user7 = User::factory()->create([
            'name_family' => 'Ramos',
            'name_first' => 'Roxanne',
            'email' => 'roxanne@mail.com',
            'office_id' => 4,
            'password' => Hash::make('abc.123')
        ]);

        $user8 = User::factory()->create([
            'name_family' => 'Coronel',
            'name_first' => 'Renz',
            'email' => 'renz@mail.com',
            'office_id' => 5,
            'password' => Hash::make('abc.123')
        ]);

        $user9 = User::factory()->create([
            'name_family' => 'Canlas',
            'name_first' => 'Joel',
            'email' => 'joel@mail.com',
            'office_id' => 6,
            'password' => Hash::make('abc.123')
        ]);

        $user10 = User::factory()->create([
            'name_family' => 'Mallari',
            'name_first' => 'Dolores',
            'email' => 'dolores@mail.com',
            'office_id' => 7,
            'password' => Hash::make('abc.123')
        ]);

        $user11 = User::factory()->create([
            'name_family' => 'Ibanez',
            'name_first' => 'Jan Michael',
            'email' => 'jm@mail.com',
            'office_id' => 6,
            'password' => Hash::make('abc.123')
        ]);

        $user12 = User::factory()->create([
            'name_family' => 'Cruz',
            'name_first' => 'Mark Paolo',
            'email' => 'mark@mail.com',
            'office_id' => 6,
            'password' => Hash::make('abc.123')
        ]);

        $user13 = User::factory()->create([
            'name_family' => 'Quioc',
            'name_first' => 'Mary Ann',
            'email' => 'mary@mail.com',
            'office_id' => 6,
            'password' => Hash::make('abc.123')
        ]);

        $user14 = User::factory()->create([
            'name_family' => 'Tibay',
            'name_first' => 'Jona',
            'email' => 'jona@mail.com',
            'office_id' => 6,
            'password' => Hash::make('abc.123')
        ]);

        $user15 = User::factory()->create([
            'name_family' => 'Bundalian',
            'name_first' => 'Rina',
            'email' => 'rina@mail.com',
            'office_id' => 6,
            'password' => Hash::make('abc.123')
        ]);

        $user16 = User::factory()->create([
            'name_family' => 'Manuel',
            'name_first' => 'Regz',
            'email' => 'regz@mail.com',
            'office_id' => 9,
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
        $user10->assignRole($roleStandard);
        $user11->assignRole($roleStandard);
        $user12->assignRole($roleStandard);
        $user13->assignRole($roleStandard);
        $user14->assignRole($roleStandard);
        $user15->assignRole($roleStandard);
        $user16->assignRole($roleAdmin);
    }
}
