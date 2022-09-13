<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'employeeId' => 1,
                'firstName' => 'Tatang',
                'lastName' => 'suherman',
                'phone' => '08121212121212',
                'email' => 'tatangSuherman1@gmail.com',
                'password' => bcrypt('tatang123'),
                'photo' => 'tatang.jpg',
                'gender' => 'man',
                'birthDate' => '2001-01-01',
                'address' => 'jl tatang suherman',
                'city' => 'Jakarta',
                'nation' => 'Indonesia',
                'roleId' => 1,
                'isActive' => true,
                'emailVerifiedAt' => now(),
                'remember_token' => '-',
                'joinedAt' => now(),
                'resignedAt' => now(),
                'statusHireId' => 1,
                'created_at' => now(),
                'updated_at' => now()
                
            ],
            [
                'employeeId' => 2,
                'firstName' => 'Tatang',
                'lastName' => 'suherman',
                'phone' => '08121212121212',
                'email' => 'tatangSuherman2@gmail.com',
                'password' => bcrypt('tatang1234'),
                'photo' => 'tatang.jpg',
                'gender' => 'man',
                'birthDate' => '2001-01-01',
                'address' => 'jl tatang suherman',
                'city' => 'Jakarta',
                'nation' => 'Indonesia',
                'roleId' => 2,
                'isActive' => true,
                'emailVerifiedAt' => now(),
                'remember_token' => '-',
                'joinedAt' => now(),
                'resignedAt' => now(),
                'statusHireId' => 2,
                'created_at' => now(),
                'updated_at' => now()
                
            ]
        ];

        DB::table('employees')->insert($permissions);
    }
}
