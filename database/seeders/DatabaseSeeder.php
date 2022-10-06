<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\AttendanceStatus;
use App\Models\FurloughType;
use App\Models\Notification;
use App\Models\StatusHire;
use App\Models\Team;
use App\Models\WorkPermit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            StatusHireSeeder::class,
            EmployeeSeeder::class,
            AttendanceStatusSeeder::class,
            AttendanceSeeder::class,
            WorkPermitSeeder::class,
            PartnerSeeder::class,
            FurloughTypeSeeder::class,
            FurloughSeeder::class,
            TeamSeeder::class,
            TeamMemberSeeder::class,
            OvertimeSeeder::class,
            NotificationSeeder::class,
            BasicSalaryByRoleSeeder::class,
            EmployeeFamilyStatusSeeder::class,
            InsuranceSeeder::class,
            InsuranceItemSeeder::class,
            TypeOfAllowanceSeeder::class,
            AllowanceSeeder::class,
        ]);
    }
}
