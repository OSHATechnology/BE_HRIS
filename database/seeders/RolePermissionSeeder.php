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
        $getListPermisID = DB::table('permissions')->pluck('permissionId');
        $getListRoleID = DB::table('roles')->pluck('roleId');
        $rolePermissions = [];
        foreach ($getListRoleID as $roleID) {
            if ($roleID == 1) {
                foreach ($getListPermisID as $permisID) {
                    $rolePermissions[] = [
                        'roleId' => $roleID,
                        'permissionId' => $permisID,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        DB::table('role_permissions')->insert($rolePermissions);
    }
}
