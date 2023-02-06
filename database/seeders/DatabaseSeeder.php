<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [['name' => 'create_user', 'guard_name' => 'api'],
            ['name' => 'update_user', 'guard_name' => 'api'],
            ['name' => 'delete_user', 'guard_name' => 'api'],
            ['name' => 'view_user', 'guard_name' => 'api'],
            ['name' => 'view_board', 'guard_name' => 'api'],
            ['name' => 'create_board', 'guard_name' => 'api'],
            ['name' => 'update_board', 'guard_name' => 'api'],
            ['name' => 'delete_board', 'guard_name' => 'api'],
            ['name' => 'view_task', 'guard_name' => 'api'],
            ['name' => 'create_task', 'guard_name' => 'api'],
            ['name' => 'delete_task', 'guard_name' => 'api'],
            ['name' => 'update_task', 'guard_name' => 'api'],
            ['name' => 'delete_label', 'guard_name' => 'api'],
            ['name' => 'update_label', 'guard_name' => 'api'],
            ['name' => 'create_label', 'guard_name' => 'api'],
            ['name' => 'view_label', 'guard_name' => 'api'],
        ];

        foreach ($permissions as $permission) {
            if (!DB::table('permissions')->where('name', $permission[ 'name' ])->exists()) {
                DB::table('permissions')->insert($permission);
            }
        }
        // Create the Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);

// Create the Product Owner role
        $productOwnerRole = Role::firstOrCreate(['name' => 'Product Owner']);

// Create the Developer role
        $developerRole = Role::firstOrCreate(['name' => 'developers']);

// Create the Tester role
        $testerRole = Role::firstOrCreate(['name' => 'testers']);

// Get all the permissions
        $permissions = Permission::all();

// Create the Super Admin user and assign all the permissions to the role
        $superAdminUser = User::firstOrCreate([
            'email' => 'admin@gateway.com',
        ], [
            'name' => 'Super Admin',
            'role_id' => $superAdminRole->id,
            'created_by' => 1,
            'password' => Hash::make('secret'),
        ]);
        $superAdminUser->assignRole($superAdminRole);
        $superAdminRole->syncPermissions($permissions);

// Create the Product Owner user and assign all the permissions to the role
        $productOwnerUser = User::firstOrCreate([
            'email' => 'product_owner@gateway.com',
        ], [
            'name' => 'Product Owner',
            'role_id' => $productOwnerRole->id,
            'created_by' => 1,
            'password' => Hash::make('secret'),
        ]);
        $productOwnerUser->assignRole($productOwnerRole);
        $productOwnerRole->syncPermissions($permissions);

// Create the Developer user and assign only the "view_board" and "view_task" permissions to the role
        $developerUser = User::firstOrCreate([
            'email' => 'developer@gateway.com',
        ], [
            'name' => 'Developer',
            'role_id' => $developerRole->id,
            'created_by' => 1,
            'password' => Hash::make('secret'),
        ]);
        $developerUser->assignRole($developerRole);
        $developerRole->syncPermissions(Permission::whereIn('name', ['view_board', 'view_task'])->get());

// Create the Tester user and assign only the "view_board" and "view_task" permissions to the role
        $testerUser = User::firstOrCreate([
            'email' => 'tester@gateway.com',
        ], [
            'name' => 'Tester',
            'role_id' => $testerRole->id,
            'created_by' => 1,
            'password' => Hash::make('secret'),
        ]);
        $testerUser->assignRole($testerRole);
        $testerRole->syncPermissions(Permission::whereIn('name', ['view_board', 'view_task'])->get());


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
