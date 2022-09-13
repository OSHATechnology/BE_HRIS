<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notif = [
            [
                'notifId' => 1,
                'empId' => 1, 
                'name' => 'tatang suherman', 
                'content' => 'Accepted furlough for 1 September 2022', 
                'type' => 'furlough', 
                'senderBy' => 1, 
                'scheduleAt' => now(), 
                'status' => 'accepted',
                'created_at' => now(), 
                'updated_at' => now()
                
            ],
            [
                'notifId' => 2,
                'empId' => 1, 
                'name' => 'tatang suherman', 
                'content' => 'Decline furlough for 5 October 2022', 
                'type' => 'furlough', 
                'senderBy' => 1, 
                'scheduleAt' => now(), 
                'status' => 'Decline',
                'created_at' => now(), 
                'updated_at' => now()
                
            ],
        ];

        DB::table('notifications')->insert($notif);
    }
}
