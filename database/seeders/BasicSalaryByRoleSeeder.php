<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicSalaryByRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salaryRole = [
            'basicSalaryByRoleId' => 1,
            'roleId' => 1,
            'fee' => 7000000, 
            'created_at' => now(), 
            'updated_at' => now()
        ];

        DB::table('basic_salary_by_roles')->insert($salaryRole);
    }
}
