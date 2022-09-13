<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FurloughSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $furlough = [
            [
                'furloughId' => 1, 
                'furTypeId' => 1, 
                'employeeId' => 2, 
                'startAt' => now(), 
                'endAt' => now(), 
                'isConfirmedBy' => true, 
                'confirmedBy' => 1, 
                'lastFurloughAt' => now(), 
                'created_at' => now(),
                'updated_at' => now()
                
            ],
        ];

        DB::table('furloughs')->insert($furlough);
    }
}
