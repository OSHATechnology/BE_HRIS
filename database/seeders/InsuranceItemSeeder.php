<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuranceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insuranceItem = [
            [
                'insItemId' => 1,
                'insuranceId' => 1,
                'name' => "BPJS Kesehatan",
                'type' => "allowance",
                'percent' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 2,
                'insuranceId' => 1,
                'name' => "BPJS Kesehatan",
                'type' => "deduction",
                'percent' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 3,
                'insuranceId' => 2,
                'name' => "Jaminan Hari Tua (JHT)",
                'type' => "allowance",
                'percent' => 3.7,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 4,
                'insuranceId' => 2,
                'name' => "Jaminan Hari Tua (JHT)",
                'type' => "deduction",
                'percent' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 5,
                'insuranceId' => 2,
                'name' => "Jaminan Kecelakaan Kerja (JKK)",
                'type' => "allowance",
                'percent' => 0.24,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 6,
                'insuranceId' => 2,
                'name' => "Jaminan Kecelakaan Kerja (JKK)",
                'type' => "allowance",
                'percent' => 0.54,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 7,
                'insuranceId' => 2,
                'name' => "Jaminan Kecelakaan Kerja (JKK)",
                'type' => "allowance",
                'percent' => 0.89,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 8,
                'insuranceId' => 2,
                'name' => "Jaminan Kecelakaan Kerja (JKK)",
                'type' => "allowance",
                'percent' => 1.27,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 9,
                'insuranceId' => 2,
                'name' => "Jaminan Kecelakaan Kerja (JKK)",
                'type' => "allowance",
                'percent' => 1.74,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 10,
                'insuranceId' => 2,
                'name' => "Jaminan Pensiun (JP)",
                'type' => "allowance",
                'percent' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'insItemId' => 11,
                'insuranceId' => 2,
                'name' => "Jaminan Pensiun (JP)",
                'type' => "deduction",
                'percent' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('insurance_items')->insert($insuranceItem);
    }
}
