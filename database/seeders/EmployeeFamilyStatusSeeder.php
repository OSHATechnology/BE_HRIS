<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeFamilyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusFamily = [
            [
                'empFamStatId' => 1, 
                'status' => 'husband', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'empFamStatId' => 2, 
                'status' => 'wife', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'empFamStatId' => 3, 
                'status' => 'the first child', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'empFamStatId' => 4, 
                'status' => 'the second child', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'empFamStatId' => 5, 
                'status' => 'the third child', 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'empFamStatId' => 6, 
                'status' => 'other children', 
                'created_at' => now(), 
                'updated_at' => now()
            ]
        ];

        DB::table('employee_family_statuses')->insert($statusFamily);
    }
}
