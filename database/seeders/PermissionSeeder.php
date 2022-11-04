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
        $permisManagement = [
            // 'Attendence Management' => [
            //     'attendance' => [
            //         ['Can view all attendance data', 'can_view_all_attendance_data'],
            //         ['Can manage status attendance employee', 'can_manage_status_attendance_employee']
            //     ],
            // ],
            'Role Management' => [
                'role' => [
                    'view_all',
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
            ],
            'Permission Management' => [
                'permission' => [
                    'view_all',
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
            ],
            'Furlough Management' => [
                'furlough' => [
                    'view_all',
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
            ],
            'Partner Management' => [
                'partner' => [
                    'view_all',
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
            ],
            'Insurance Management' => [
                'insurance' => [
                    'view_all',
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
            ],
            'Insurance Item Management' => [
                'insurance_item' => [
                    'view_all',
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
            ],
        ];
        foreach ($permisManagement as $keyP => $item) {
            foreach ($item as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $data[] = [
                        'namePermission' => 'Can ' . $value2 . ' ' . $key,
                        'description' => 'User can ' . $value2 . ' ' . $key,
                        'tag' => $keyP,
                        'slug' => ($value2 == 'view_all') ?  'can_' . $value2 . '_' . $key . 's' :  'can_' . $value2 . '_' . $key,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }

        DB::table('permissions')->insert($data);
    }
}
