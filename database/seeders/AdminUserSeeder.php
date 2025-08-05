<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role_id' => $adminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
