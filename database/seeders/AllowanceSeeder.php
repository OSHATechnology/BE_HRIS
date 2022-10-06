<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allowance = [
            [
                "allowanceId" => 1,
                "roleId" => 1,
                "typeId" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "allowanceId" => 2,
                "roleId" => 1,
                "typeId" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "allowanceId" => 3,
                "roleId" => 2,
                "typeId" => 1,
                "created_at" => now(),
                "updated_at" => now()
            ],
            [
                "allowanceId" => 4,
                "roleId" => 2,
                "typeId" => 2,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];

        DB::table('allowances')->insert($allowance);
    }
}
