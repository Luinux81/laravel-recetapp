<?php

namespace Database\Factories;

use App\Models\PasoReceta;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasoRecetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PasoReceta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "orden" => "1",
            "texto" => $this->faker->text(100)
        ];
    }
}
