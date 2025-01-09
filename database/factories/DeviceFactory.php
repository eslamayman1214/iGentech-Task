<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class DeviceFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'device_type' => $this->faker->randomElement(['mobile', 'tablet']),
            'device_id' => $this->faker->uuid,
            'fcm_token' => $this->faker->unique()->sha256,
        ];
    }
}
