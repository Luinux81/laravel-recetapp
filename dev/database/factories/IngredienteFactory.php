<?php

namespace Database\Factories;

use App\Models\CategoriaIngrediente;
use App\Models\Ingrediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ingrediente::class;

    public $ingredientes = [
        'zanahoria','cebolla','pimiento rojo','pimiento verde','ajo','apio',
        'coliflor','brocoli','repollo','lechuga','tomate','patata',
        'huevo','pollo','cerdo','ternera','dorada','sardinas','choco'
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $categorias = CategoriaIngrediente::pluck('id')->toArray();

        if(!empty($categorias)){
            $cat = $this->faker->randomElement($categorias);
        }
        else{
            $cat = NULL;
        }

        return [
            'user_id'            => NULL,
            'cat_id'             => $cat,
            'nombre'             => $this->faker->passthrough($this->getRandomNombreIngrediente()),
            'descripcion'        => $this->faker->text(),
            'marca'              => $this->faker->word(),
            'barcode'            => $this->faker->ean13(),
            'imagen'             => NULL,
            'url'                => $this->faker->url(),
                
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
        ];
    }

    private function getRandomNombreIngrediente(){
        $index = $this->faker->numberBetween(0,count($this->ingredientes)-1);
        return $this->ingredientes[$index];
    }
}
