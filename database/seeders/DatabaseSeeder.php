<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $getRole = Role::where('name','Super Admin')->first();
        if(!$getRole)
        {
            $getRole =  Role::create([
                'name' => 'Super Admin',
                'guard_name'=>'api'
            ]);
        }

        $getAdmin = User::where('email','zardasht@gmail.com')->first();
        if(!$getAdmin)
        {
            $insertAdmin = User::create([
                'name' => fake()->name(),
                'email' => 'admin@demo.com',
                'role_id' => $getRole->id,
                'created_by' => 1,
                'password' => Hash::make('12345678'), // password
            ]);
            $insertAdmin->assignRole($getRole->id);
        }

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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
