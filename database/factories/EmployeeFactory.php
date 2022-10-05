<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'photo' => 'emp_img.png',
            'gender' => $this->faker->randomElement(['man', 'woman']),
            'birthDate' => $this->faker->date(),
            'address' => $this->faker->address,
            'city' => $this->faker->city,
            'nation' => $this->faker->country,
            'roleId' => 2,
            'isActive' => 1,
            'joinedAt' => $this->faker->date(),
            'statusHireId' => 1,
        ];
    }
}
