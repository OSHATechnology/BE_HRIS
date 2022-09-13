<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $partner = [
            [
                'partnerId' => 1,
                'name' => 'PT. Senang Bersama',
                'description' => 'PT yang selalu bikin senyum',
                'resposibleBy' => 'HR PT. Senang Bersama',
                'phone' => '081212121212',
                'address' => 'Jl Terusan Cilandak',
                'photo' => 'Senang Bersama.jpg',
                'assignedBy' => 1,
                'joinedAt' => now(),
                'created_at' => now(),
                'updated_at' => now()
                
            ],
        ];

        DB::table('partners')->insert($partner);
    }
}
