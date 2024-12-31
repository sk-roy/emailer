<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    // protected $model = Email::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence(),
            'email' => $this->faker->unique()->safeEmail(),
            'message' => $this->faker->paragraph(),
            'attachment_filename' => $this->faker->word() . '.pdf',
            'status' => $this->faker->randomElement(['sent', 'pending', 'failed']),
        ];
    }
}
