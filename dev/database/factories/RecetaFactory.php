<?php

namespace Database\Factories;

use App\Models\CategoriaReceta;
use App\Models\Receta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class RecetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categorias = CategoriaReceta::pluck('id')->toArray();
        if(!empty($categorias)){
            $cat = $this->faker->randomElement($categorias);
        }
        else{
            $cat = NULL;
        }

        return [
            'user_id'            => NULL,
            'cat_id'             => $cat,
            'nombre'             => $this->faker->text(30),
            'descripcion'        => $this->faker->text(),
            'imagen'             => NULL,
            'raciones'           => $this->faker->randomNumber(1, true),
            'tiempo'             => $this->faker->randomNumber(2, false) . " min",
            'calorias'           => $this->faker->randomNumber(3, false),
            'fat_total'          => $this->faker->randomFloat(1,20,30),
            'fat_saturadas'      => $this->faker->randomFloat(1,20,30),
            'fat_poliinsaturadas'=> $this->faker->randomFloat(1,20,30),
            'fat_monoinsaturadas'=> $this->faker->randomFloat(1,20,30),
            'fat_trans'          => $this->faker->randomFloat(1,20,30),
            'colesterol'         => $this->faker->randomFloat(1,20,30),
            'sodio'              => $this->faker->randomFloat(1,20,30),
            'potasio'            => $this->faker->randomFloat(1,20,30),
            'fibra'              => $this->faker->randomFloat(1,20,30),
            'carb_total'         => $this->faker->randomFloat(1,20,30),
            'carb_azucar'        => $this->faker->randomFloat(1,20,30),
            'proteina'           => $this->faker->randomFloat(1,20,30),
            'publicado'          => false,
        ];
    }
}
