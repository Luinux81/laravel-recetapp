<?php

namespace Database\Seeders;

use App\Helpers\Terminal;
use App\Helpers\WebScrapper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaIngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setCategoriasAutoFile();
        //$this->setCategoriasAutoFatSecret();
        //$this->setCategoriasManual();
    }

    private function setCategoriasAutoFile(){
        $path = "storage/seeds/categorias_ingrediente_data.sql";
        
        DB::unprepared(file_get_contents($path));

        $this->command->info('CategoriasIngrediente table seeded!');
    }

    private function setCategoriasAutoFatSecret(){
        echo "\nObteniendo categorias de ingredientes de Fat Secret";

        $categorias = WebScrapper::getAllCategorias();

        $numCategorias = 0;
        foreach ($categorias as $key => $value) {
            $numCategorias++;
            if(is_array($value->subcategorias)){
                $numCategorias = $numCategorias + count($value->subcategorias);
            }
        }

        echo "\n" . $numCategorias . " categorias encontradas.\n";

        $i = 0;
        foreach ($categorias as $key => $categoria) {
            $id = $this->save(1,$categoria->nombre,$categoria->url);
            $i++;

            Terminal::consoleProgressBar($i,$numCategorias);

            foreach ($categoria->subcategorias as $subcat => $url) {
                $this->save(1,$subcat, $url, $id);
                $i++;
                Terminal::consoleProgressBar($i,$numCategorias);
            }
        }

        echo "\n\n";
    }

    private function setCategoriasManual(){
        $id = $this->save(1, "Productos frescos", "Sólo productos frescos");
        $this->save(1, "Frutas", "Frutas", $id);
        $this->save(1, "Verdura", "Verduras", $id);
        $this->save(1, "Cereales", "Cereales", $id);
        $this->save(1, "Carnes", "Carnes", $id);
        $this->save(1, "Pescados", "Pescados", $id);

        $id = $this->save(1, "Productos preparados", "Sólo productos preparados");
        $this->save(1, "Salsas", "Salsas", $id);
        $this->save(1, "Embutidos", "Embutidos", $id);
    }

    private function save($user_id, $nombre, $descripcion, $parent = NULL){        
        $params = [
            "user_id" => $user_id,
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "catParent_id" => $parent,
        ];
        
        return DB::table("categorias_ingrediente")->insertGetId($params);
    }
}
