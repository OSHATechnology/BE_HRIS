<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolePermissions = [
            [
                'roleId' => 1,
                'permissionId' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'roleId' => 2,
                'permissionId' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('role_permissions')->insert($rolePermissions);
    }
}
