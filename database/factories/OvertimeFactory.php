<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Overtime>
 */
class OvertimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['acc', 'pending']);
        $empId = $this->faker->numberBetween(2, 40);

        $time = now();
        switch ($type) {
            case 'acc':
                $startAt = $time;
                $endAt = now()->addHours($this->faker->numberBetween(2, 3));
                return [
                    'employeeId'  => $empId,
                    'startAt' => $startAt,
                    'endAt' => $endAt,
                    'assignedBy' => $empId,
                    'isConfirmed' => 1,
                    'confirmedBy' => 1, //id admin
                ];
                break;

            default:
                $startAt = $time;
                $endAt = now()->addHours($this->faker->numberBetween(2, 5));
                return [
                    'employeeId'  => $empId,
                    'startAt' => $startAt,
                    'endAt' => $endAt,
                    'assignedBy' => $empId,
                    'isConfirmed' => 0,
                    'message' => $this->faker->text(100),
                ];
                break;
        }
    }
}
