<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_number' => fake()->unique()->numerify('2026#####'),
            'name' => fake()->name(),
            'course' => fake()->randomElement(['BSIT', 'BSCS', 'BSBA', 'BSEd']),
            'year_level' => fake()->numberBetween(1, 4),
        ];
    }
}
