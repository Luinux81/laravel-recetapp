<?php
namespace App\Helpers;

use App\Models\Receta;

class Seeder{

    public static function actualizaSeedFileRecetas(Receta $receta)
    {
        $path = base_path() . "/storage/seeds/recetas.sql";        
        $content = file_get_contents($path);

        $lineas = explode(PHP_EOL,$content);        
        $res = [];

        $encontrado = false;
        $index_id = 0;
        $sql_receta = Seeder::transformaRecetaASql($receta);
        
        foreach($lineas as $linea){
            preg_match("/\([0-9][0-9]*\,/",$linea,$matches);
            
            if (count($matches) > 0){   
                //Es una linea con valores para insert
                $index_id++;            
                $auxId = $matches[0];
                $auxId = substr($auxId,1,strlen($auxId)-2);

                if($receta->id == $auxId){
                    $encontrado = true;
                    $ultimoCaracter = substr($linea,strlen($linea)-1);
                    $res []= $sql_receta . $ultimoCaracter;
                }
                else{
                    $res []= $linea;
                }
            }
            else{
                $res []= $linea;
            }
        }

        $content = implode(PHP_EOL,$res);


        if(!$encontrado){            
            $buscar = ";" . PHP_EOL . "COMMIT;";
            $reemplazo = "," . PHP_EOL . $sql_receta . ";" . PHP_EOL . "COMMIT;";

            $content = str_replace($buscar ,$reemplazo,$content);
        }
        else{
            $content = implode(PHP_EOL,$res);
        }

        file_put_contents($path,$content);
    }

    public static function actualizaSeederFileIngredientesReceta(Receta $receta){
        $path = base_path() . "/storage/seeds/ingrediente_receta.sql";        
        $content = file_get_contents($path);

        $lineas = explode(PHP_EOL,$content);
        $res = [];

        foreach ($lineas as $linea){
            if((substr($linea,0,1) == "(") && (substr($linea,strlen($linea)-2,1) == ")")){
                $aux = explode(",",$linea);
                if(trim($aux[1]) != $receta->id){
                    $res []= $linea;    
                }
                else{
                    if(substr($linea, strlen($linea)-1) == ";"){
                        $res []= ";";
                    }
                }
            }
            else{
                $res []= $linea;
            }
        }

        $content = implode(PHP_EOL,$res);

        $buscar = ";" . PHP_EOL . "COMMIT;";
        $reemplazo = ",". PHP_EOL . Seeder::transformaIngredientesRecetaASql($receta) . ";" . PHP_EOL . "COMMIT;";

        $content = str_replace($buscar, $reemplazo, $content);

        file_put_contents($path,$content);
    }

    public static function actualizaSeederFilePasosReceta(Receta $receta){
        $path = base_path() . "/storage/seeds/pasos_receta.sql";
        $content = file_get_contents($path);

        $lineas = explode(PHP_EOL,$content);
        $res = [];

        foreach($lineas as $linea){
            if((substr($linea,0,1) == "(") && (substr($linea,strlen($linea)-2,1) == ")")){
                $aux = explode(",",$linea);
                if(trim($aux[1]) != $receta->id){
                    $res []= $linea;    
                }
                else{
                    if(substr($linea, strlen($linea)-1) == ";"){
                        $res []= ";";
                    }
                }
            }
            else{
                $res []= $linea;
            }
        }

        $content = implode(PHP_EOL,$res);

        $buscar = ";" . PHP_EOL . "COMMIT;";
        $reemplazo = "," . PHP_EOL .Seeder::transformaPasosRecetaASql($receta) . ";" . PHP_EOL . "COMMIT;";

        $content = str_replace($buscar, $reemplazo, $content);

        file_put_contents($path,$content);
    }

    private static function transformaRecetaASql(Receta $receta){
        $res = "(";
        $res .= $receta->id . ",NULL,";

        if($receta->cat_id != NULL){
            $res .= "'" . $receta->cat_id . "',";
        }
        else{
            $res .= "NULL,";
        }
        
        if($receta->nombre != NULL){
            $res .= "'" . $receta->nombre . "',";
        }
        else{
            $res .= "NULL,";
        }

        if($receta->descripcion != NULL){
            $res .= "'" . $receta->descripcion . "',";
        }
        else{
            $res .= "NULL,";
        }

        $res .= $receta->raciones . ",";

        if($receta->tiempo != NULL){
            $res .= "'" . $receta->tiempo . "',";
        }
        else{
            $res .= "NULL,";
        }

        if($receta->imagen != NULL){
            $res .= "'" . $receta->imagen . "',";
        }
        else{
            $res .= "NULL,";
        }


        $res .= ($receta->calorias?$receta->calorias:'NULL') . ",";
        $res .= ($receta->fat_total?$receta->fat_total:'NULL') . ",";
        $res .= ($receta->fat_saturadas?$receta->fat_saturadas:'NULL') . ",";
        $res .= ($receta->fat_poliinsaturadas?$receta->fat_poliinsaturadas:'NULL') . ",";
        $res .= ($receta->fat_monoinsaturadas?$receta->fat_monoinsaturadas:'NULL') . ",";
        $res .= ($receta->fat_trans?$receta->fat_trans:'NULL') . ",";
        $res .= ($receta->colesterol?$receta->colesterol:'NULL') . ",";
        $res .= ($receta->sodio?$receta->sodio:'NULL') . ",";
        $res .= ($receta->potasio?$receta->potasio:'NULL') . ",";
        $res .= ($receta->fibra?$receta->fibra:'NULL') . ",";
        $res .= ($receta->carb_total?$receta->carb_total:'NULL') . ",";
        $res .= ($receta->carb_azucar?$receta->carb_azucar:'NULL') . ",";
        $res .= ($receta->proteina?$receta->proteina:'NULL') . ",";

        if($receta->created_at != NULL){
            $res .= "'" . $receta->created_at . "',";
        }
        else{
            $res .= "NULL,";
        }

        if($receta->updated_at != NULL){
            $res .= "'" . $receta->updated_at . "'";
        }
        else{
            $res .= "NULL";
        }

        $res .= ")";

        return $res;
    }

    private static function transformaIngredientesRecetaASql(Receta $receta){
        $aux = "";
        foreach($receta->ingredientes()->get() as $componente){
            $aux .= "(";
            $aux .= $componente->id . ", ";
            $aux .= $receta->id . ", ";
            $aux .= $componente->pivot->cantidad . ", ";
            $aux .= "'" . $componente->pivot->unidad_medida . "'),\n";
        }

        return substr($aux,0,strlen($aux)-2);
    }

    private static function transformaPasosRecetaASql(Receta $receta){
        $aux = "";
        foreach($receta->pasos()->get() as $paso){
            $aux .= "(";
            $aux .= $paso->id . ", ";
            $aux .= $receta->id . ", ";
            $aux .= $paso->orden . ", ";
            $aux .= "'" . $paso->texto . "', NULL),\n";
        }

        return substr($aux,0,strlen($aux)-2);
    }
}

?>