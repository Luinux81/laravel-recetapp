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
            "cat_id"  => "9",
            "url" => "https://www.bedca.net/bdpub/index.php",
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
            "url" => "https://www.bedca.net/bdpub/index.php",
        ]);

        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Ajo",
            "descripcion" => "Ajo crudo",
            "calorias" => "117",
            "fat_total" => "0.23",
            "fat_saturadas" => "0.05",
            "fat_poliinsaturadas" => "0.1",
            "fat_monoinsaturadas" => "0.03",
            "fat_trans" => "0",
            "colesterol" => "0",
            "sodio" => "19",
            "potasio" => "446",
            "fibra" => "1.2",
            "carb_total" => "24.3",
            "carb_azucar" => "0",
            "proteina" => "3.9",
            "cat_id"  => "3",
            "url" => "https://www.bedca.net/bdpub/index.php",
        ]);

        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Apio",
            "descripcion" => "Apio crudo",
            "calorias" => "11",
            "fat_total" => "0.1",
            "fat_saturadas" => "0",
            "fat_poliinsaturadas" => "0",
            "fat_monoinsaturadas" => "0",
            "fat_trans" => "0",
            "colesterol" => "0",
            "sodio" => "110",
            "potasio" => "305",
            "fibra" => "2",
            "carb_total" => "1.5",
            "carb_azucar" => "0",
            "proteina" => "0.9",
            "cat_id"  => "3",
            "url" => "https://www.bedca.net/bdpub/index.php",
        ]);        

        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Berenjena",
            "descripcion" => "Berenjena cruda",
            "calorias" => "20",
            "fat_total" => "0.2",
            "fat_saturadas" => "0",
            "fat_poliinsaturadas" => "0",
            "fat_monoinsaturadas" => "0",
            "fat_trans" => "0",
            "colesterol" => "0",
            "sodio" => "3",
            "potasio" => "262",
            "fibra" => "2.4",
            "carb_total" => "3.8",
            "carb_azucar" => "0",
            "proteina" => "0.7",
            "cat_id"  => "3",
            "url" => "https://www.bedca.net/bdpub/index.php",
        ]); 

        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Brocoli",
            "descripcion" => "Brocoli crudo",
            "calorias" => "26",
            "fat_total" => "0.4",
            "fat_saturadas" => "0.07",
            "fat_poliinsaturadas" => "0.2",
            "fat_monoinsaturadas" => "0",
            "fat_trans" => "0",
            "colesterol" => "0",
            "sodio" => "13",
            "potasio" => "370",
            "fibra" => "3",
            "carb_total" => "2.4",
            "carb_azucar" => "0",
            "proteina" => "3",
            "cat_id"  => "3",
            "url" => "https://www.bedca.net/bdpub/index.php",
        ]); 

        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Huevo",
            "descripcion" => "Huevo crudo",
            "calorias" => "150",
            "fat_total" => "11.1",
            "fat_saturadas" => "3.1",
            "fat_poliinsaturadas" => "1.74",
            "fat_monoinsaturadas" => "3.97",
            "fat_trans" => "0",
            "colesterol" => "385",
            "sodio" => "140",
            "potasio" => "130",
            "fibra" => "0",
            "carb_total" => "0",
            "carb_azucar" => "0",
            "proteina" => "12.5",
            "cat_id"  => "1",
            "url" => "https://www.bedca.net/bdpub/index.php",
        ]); 


        DB::table("ingredientes")->insert([
            "user_id" => 1,
            "nombre" => "Tomate frito",
            "descripcion" => "Tomate frito",
            "marca" => "Hacendado",
            "barcode" => "8480000171511",
            "calorias" => "77",
            "fat_total" => "3.5",
            "fat_saturadas" => "0.3",
            "fat_poliinsaturadas" => "0",
            "fat_monoinsaturadas" => "0",
            "fat_trans" => "0",
            "colesterol" => "0",
            "sodio" => "0",
            "potasio" => "0",
            "fibra" => "0",
            "carb_total" => "9.5",
            "carb_azucar" => "6.9",
            "proteina" => "1.5",
            "cat_id"  => "6",
            "url" => "https://www.fatsecret.es/calor%C3%ADas-nutrici%C3%B3n/hacendado/tomate-frito/100g",
        ]); 
        // $factoria = Ingrediente::factory();
        // //$num = count($factoria->ingredientes);
        // $num = 5;

        // $ingredientes = $factoria->count($num)->make();

        // foreach ($ingredientes as $ingrediente){
        //     $ingrediente->save();
        // }
    }
}
