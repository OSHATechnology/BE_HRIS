<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'roleId' => 1,
                'nameRole' => 'admin',
                'description' => 'this is admin role for employee management',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'roleId' => 2,
                'nameRole' => 'employee',
                'description' => 'this is employee role',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
