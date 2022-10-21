<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        info("Cron Job running at " . now());

        $resp = Http::get('https://jsonplaceholder.typicode.com/users');

        $users = $resp->json();

        if (!empty($users)) {
            foreach ($users as $user) {
                if (!Employee::where('email', $user['email'])->exists()) {
                    Employee::create([
                        'firstName' => $user['name'],
                        'lastName' => "API",
                        'phone' => $user['phone'],
                        'email' => $user['email'],
                        'password' => bcrypt('password'),
                        'address' => $user['address']['street'],
                        'photo' => "https://ui-avatars.com/api/?name=" . $user['name'],
                        'gender' => "man",
                        'city' => "Jakarta",
                        'nation' => "api",
                        'roleId' => 2,
                        'isActive' => 1,
                        'birthDate' => "2021-01-01",
                        'statusHireId' => 1,
                    ]);
                }
            }
        }

        return 0;
    }
}
