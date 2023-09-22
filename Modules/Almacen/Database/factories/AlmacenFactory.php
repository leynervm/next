<?php
namespace Modules\Almacen\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AlmacenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Almacen\Entities\Almacen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}

