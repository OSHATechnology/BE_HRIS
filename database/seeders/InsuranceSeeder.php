<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insurance = [
            [
                'insuranceId' => 1, 
                'name' => "kesehatan", 
                'companyName' => "BPJS Kesehatan", 
                'address' => "Jl. Letjen Suprapto Kav. 20 No. 14 Cempaka Putih, Jakarta Pusat 10510"
            ],
            [
                'insuranceId' => 2, 
                'name' => "Ketenagakerjaan", 
                'companyName' => "BPJS Ketenagakerjaan", 
                'address' => "Jl. Gatot Subroto No.79, Karet Semanggi, Kecamatan Setiabudi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12930"
            ]
        ];

        DB::table('insurances')->insert($insurance);
    }
}
