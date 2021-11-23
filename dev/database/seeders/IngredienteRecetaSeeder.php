<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CategoriaIngrediente;

class IngredienteRecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // DB::table("ingrediente_receta")->insert([
        //     "ingrediente_id" => 1,
        //     "receta_id" => 1,
        //     "cantidad" => 250,
        //     "unidad_medida" => "gr",
        // ]);

        // DB::table("ingrediente_receta")->insert([
        //     "ingrediente_id" => 2,
        //     "receta_id" => 1,
        //     "cantidad" => 40,
        //     "unidad_medida" => "gr",
        // ]);
    }
}
