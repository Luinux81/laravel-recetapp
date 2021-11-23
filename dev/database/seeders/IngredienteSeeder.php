<?php

namespace Database\Seeders;

use stdClass;
use App\Helpers\Terminal;
use App\Models\Ingrediente;
use App\Helpers\WebScrapper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CategoriaIngrediente;


class IngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->seedIngredientesManual();
        //$this->seedIngredientesAutoFatSecret();
        $this->seedIngredientesAutoFile();
    }

    public function seedIngredientesAutoFile(){
        $path = "storage/seeds/ingredientes_data_temp.sql";
        
        DB::unprepared(file_get_contents($path));

        $this->command->info('Ingredientes table seeded!');
    }

    private function seedIngredientesManual(){
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
    }

    private function seedIngredientesAutoFatSecret(){
        $categorias = CategoriaIngrediente::all();
        $acumulados = [];

        foreach($categorias as $categoria){
            
            if($categoria->categoriaRaiz()){
                $ingredientes = WebScrapper::getIngredientesPorCategoriaFatSecret($categoria->descripcion);
                
                if(!empty($ingredientes)){
                    foreach ($ingredientes as $ingrediente) {
                        if(is_array($ingrediente->listaIngredientes)){
                            foreach ($ingrediente->listaIngredientes as $valor) {
                                $obj = new stdClass();
                                $obj->categoria = $ingrediente->categoria;
                                $obj->nombre = $valor->nombre;
                                $obj->url = $valor->url;
                                array_push($acumulados, $obj);
                                Terminal::consoleFixedText(count($acumulados) . " ingredientes descubiertos.");
                            }
                        }
                    }
                }              
            }
        }

        $total = count($acumulados);
        $this->command->info("\n" . $total . " ingredientes descubiertos.");

        $this->command->warn("Procesando ingredientes:");

        $i = 0;
        foreach ($acumulados as $linkIngrediente) {
            $ingrediente_fs = WebScrapper::getIngredienteFatSecret($linkIngrediente->url);
            
            //$array[$i++] = $ingrediente_fs;

            $array[$i++] = Ingrediente::create([
                "user_id"             => "1",
                "cat_id"              => $this->getIdCategoriaPorNombre($linkIngrediente->categoria),
                "nombre"              => $linkIngrediente->nombre,                
                "url"                 => $linkIngrediente->url,
                "calorias"            => $ingrediente_fs->calorias,
                "fat_total"           => $ingrediente_fs->fat_total,
                "fat_saturadas"       => $ingrediente_fs->fat_saturadas,
                "fat_poliinsaturadas" => $ingrediente_fs->fat_poliinsaturadas,
                "fat_monoinsaturadas" => $ingrediente_fs->fat_monoinsaturadas,
                "colesterol"          => $ingrediente_fs->colesterol,
                "potasio"             => $ingrediente_fs->potasio,
                "fibra"               => $ingrediente_fs->fibra,
                "carb_total"          => $ingrediente_fs->carb_total,
                "carb_azucar"         => $ingrediente_fs->carb_azucar,
                "proteina"            => $ingrediente_fs->proteina,
            ]);

            Terminal::consoleProgressBar($i,$total);

            //if($i == 10) break;
        }
        $this->command->info($total . " ingredientes procesandos");
        //dd($array);
    }

    private function getIdCategoriaPorNombre($nombre){
        $categoria = CategoriaIngrediente::where('nombre',$nombre)->first();

        if($categoria){
            return $categoria->id;
        }
        else{
            return null;
        }
    }
}
