<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FurloughTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $furloughType = [
            [
                'furTypeId' => 1, 
                'name' => 'pregnant', 
                'type' => 'monthly', 
                'max' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
        ];

        DB::table('furlough_types')->insert($furloughType);
    }
}
