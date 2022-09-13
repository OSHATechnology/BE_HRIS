<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'permissionId' => 1,
                'namePermission' => 'Can view all attendance data',
                'description' => 'user can view all attendance data',
                'tag' => 'Attendence Management',
                'slug' => 'can_view all_attendance_data',
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'permissionId' => 2,
                'namePermission' => 'Can manage status attendance employee',
                'description' => 'User can manage status attendance employee',
                'tag' =>'Attendence Management',
                'slug' => 'can_manage_status_attendance_employee',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('permissions')->insert($permissions);
    }
}
