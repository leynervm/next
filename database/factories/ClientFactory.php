<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $digits = $this->faker->randomElement([8,11]);
        return [
            'document' => $digits == '8' ? $this->faker->unique()->numerify('#########') : $this->faker->unique()->numerify('###########'),
            'name' => $this->faker->name(),
            'date' => $this->faker->date(),
            'email' => $this->faker->email(),
            'nacimiento' => $this->faker->date(),
            'sexo' => $digits == '8' ? $this->faker->randomElement(['M','F']) : 'E',
            'pricetype_id' => $this->faker->randomElement([1,2,3]),
            'channelsale_id' => 1,
        ];
    }
}
