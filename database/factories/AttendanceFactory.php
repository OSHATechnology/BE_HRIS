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
        $type = $this->faker->numberBetween(1, 3);
        $empId = $this->faker->numberBetween(2, 10);
        $dateTime = $this->faker->dateTimeBetween('-1 month', 'now');
        switch ($type) {
            case 1:
                $inOut = $this->faker->randomElement(['in', 'out']);
                break;

            default:
                $inOut = '-';
                break;
        }
        return [
            'employeeId' => $empId,
            'attendanceStatusId' => $type,
            'submitedAt' => $dateTime,
            'submitedById' => $empId,
            'typeInOut' => $inOut,
            'timeAttend' => $dateTime,
        ];
    }
}
