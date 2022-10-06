<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeOfAllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeOfAllowance = [
            [
                'typeId' => 1,
                'name' => 'makan',
                'nominal' => 3000000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'typeId' => 2,
                'name' => 'kacamata',
                'nominal' => 2000000,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('type_of_allowances')->insert($typeOfAllowance);
    }
}
