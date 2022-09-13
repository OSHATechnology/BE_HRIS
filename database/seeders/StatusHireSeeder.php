<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusHireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusHire = [
            [
                'statusHireId' => 1,
                'name' => "on duty",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'statusHireId' => 2,
                'name' => 'resign',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('status_hires')->insert($statusHire);
    }
}
