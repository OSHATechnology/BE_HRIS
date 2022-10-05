<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $empId = $this->faker->numberBetween(2, 10);
        $dateTime = $this->faker->dateTimeBetween('-1 month', 'now');
        return [
            'employeeId' => $empId,
            'attendanceStatusId' => 1,
            'submitedAt' => $dateTime,
            'submitedById' => $empId,
            'typeInOut' => $this->faker->randomElement(['in', 'out']),
            'timeAttend' => $dateTime,
        ];
    }
}
