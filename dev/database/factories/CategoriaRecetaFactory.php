<?php

namespace Database\Factories;

use App\Models\CategoriaReceta;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaRecetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CategoriaReceta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => 1,
            "nombre" => $this->faker->text(20),            
            "descripcion" => $this->faker->text(60),
            "publicado" => false,
        ];
    }
}
