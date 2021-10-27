<?php

namespace Database\Seeders;

use App\Models\Ingrediente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Arroz",
            "descripcion" => "",
            "calorias" => "387",
            "fat_total" => "0.9",
            "fat_saturadas" => "0.21",
            "fat_poliinsaturadas" => "0.32",
            "fat_monoinsaturadas" => "0.23",
            "fat_trans" => "0",
            "colesterol" => "0",
            "sodio" => "6",
            "potasio" => "110",
            "fibra" => "0.2",
            "carb_total" => "86",
            "carb_azucar" => "0",
            "proteina" => "7",
        ]);

        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Aceite de oliva virgen extra",
            "descripcion" => "",
            "calorias" => "888",
            "fat_total" => "100",
            "fat_saturadas" => "14.21",
            "fat_poliinsaturadas" => "7.5",
            "fat_monoinsaturadas" => "78.2",
            "fat_trans" => "0",
            "colesterol" => "0",
            "sodio" => "0",
            "potasio" => "0",
            "fibra" => "0",
            "carb_total" => "0",
            "carb_azucar" => "0",
            "proteina" => "0",
        ]);

        $factoria = Ingrediente::factory();
        //$num = count($factoria->ingredientes);
        $num = 5;

        $ingredientes = $factoria->count($num)->make();

        foreach ($ingredientes as $ingrediente){
            $ingrediente->save();
        }
    }
}
