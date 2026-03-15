<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profile;
use App\Models\User;

class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'postcode' => $this->faker->regexify('[0-9]{3}-[0-9]{4}'),
            'address'  => $this->faker->prefecture() . $this->faker->city(),
            'building' => 'マンション' . $this->faker->numberBetween(101, 999),
            'img_url'  => null,
        ];
    }
}
