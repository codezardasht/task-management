<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('permissions')->insert([
            [
                'name' => 'create_user',
                'guard_name' => 'api'
            ],
            [
                'name' => 'update_user',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_user',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view_user',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view_board',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create_board',
                'guard_name' => 'api'
            ],
            [
                'name' => 'update_board',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_board',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view_task',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create_task',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_task',
                'guard_name' => 'api'
            ],
            [
                'name' => 'update_task',
                'guard_name' => 'api'
            ],
            [
                'name' => 'delete_label',
                'guard_name' => 'api'
            ],
            [
                'name' => 'update_label',
                'guard_name' => 'api'
            ],
            [
                'name' => 'create_label',
                'guard_name' => 'api'
            ],
            [
                'name' => 'view_label',
                'guard_name' => 'api'
            ],
        ]);

        $productOwnerRole = Role::create(['name' => 'Product Owner']);
        $developerRole = Role::create(['name' => 'Developer']);
        $testerRole = Role::create(['name' => 'Tester']);

        $productOwnerRole->givePermissionTo([
            'create_user',
            'update_user',
            'delete_user',
            'view_user',
            'create_board',
            'update_board',
            'delete_board',
            'view_board',
            'create_task',
            'update_task',
            'delete_task',
            'view_task',
            'create_label',
            'update_label',
            'delete_label',
            'view_label',

        ]);

    }
}
