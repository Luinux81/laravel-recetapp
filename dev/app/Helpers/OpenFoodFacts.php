<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use OpenFoodFacts\Laravel\Facades\OpenFoodFacts as OFFs;

class OpenFoodFacts
{
    public static $dataset = [
        '8437004621153',
        '8480000808585',
        '8480012010334',
        "vino_err" => '8480012030011',
        '8480012012611',
        '8480012009017',
        '8480012006023',
        '8436042305407',
        '8480012028391',
        "queso" => '2350918003357',
        "tsatsiki" => '8480000809803',
        'donuts' => '8410022110472', 
        'crema_avellana' => '8480000230447',
    ];

    public static function getProductosOFFByCode(array $codigos = []) : Collection
    {
        $res = collect();

        foreach ($codigos as $codigo) {
            $res->add(OpenFoodFacts::getProductoOFFByCode($codigo));
        }

        return $res;
    }

    public static function getProductoOFFByCode(string $codigo) 
    {
        $res = [];
        $data = OFFs::barcode($codigo);

        if(!empty($data)){
            $res = OpenFoodFacts::validar($data);
        }
        
        dd($res, $data);

        return $res;
    }

    private static function validar(array $data)
    {
        $res = [
            "nombre" => "",
            "descripcion" => "",
            "marca" => "",
            "barcode" => "",
            "image" => "",
            "calorias" => "",
            "carb_total" => "",
            "carb_azucar" => "",
            "proteina" => "",
            "fat_total" => "",
            "fat_saturadas" => "",
            "fat_poliinsaturadas" => "",
            "fat_monoinsaturadas" => "",
            "fat_trans" => "",
            "colesterol" => "",
            "sodio" => "",
            "potasio" => "",
            "sal" => "",
            "fibra" => "",
        ];

        if(array_key_exists("product_name_es", $data)){
            $res["nombre"] = $data["product_name_es"];
        }
        
        if(array_key_exists("ingredients_text", $data)){
            $res["descripcion"] = $data["ingredients_text"];
        }
        
        if(array_key_exists("brands", $data)){
            $res["marca"] = $data["brands"];
        }

        if(array_key_exists("id", $data)){
            $res["barcode"] = $data["id"];
        }

        if(array_key_exists("image_url", $data)){
            $res["image"] = $data["image_url"]; //TODO: Mirar a ver si imagen se puede guardar o hacerlo luego   
        }

        if(array_key_exists("nutriments", $data)){

            if(array_key_exists("energy-kcal_100g", $data["nutriments"])){
                $res["calorias"] = $data["nutriments"]["energy-kcal_100g"];
            }

            if(array_key_exists("carbohydrates_100g", $data["nutriments"])){
                $res["carb_total"] = $data["nutriments"]["carbohydrates_100g"];
            }

            if(array_key_exists("sugars_100g", $data["nutriments"])){
                $res["carb_azucar"] = $data["nutriments"]["sugars_100g"];
            }

            if(array_key_exists("proteins_100g", $data["nutriments"])){
                $res["proteina"] = $data["nutriments"]["proteins_100g"];
            }

            if(array_key_exists("fat_100g", $data["nutriments"])){
                $res["fat_total"] = $data["nutriments"]["fat_100g"];
            }

            if(array_key_exists("saturated-fat_100g", $data["nutriments"])){
                $res["fat_saturadas"] = $data["nutriments"]["saturated-fat_100g"];
            }


            if(array_key_exists("polyunsaturated-fat_100g", $data["nutriments"])){
                $res["fat_poliinsaturadas"] = $data["nutriments"]["polyunsaturated-fat_100g"];
            }


            if(array_key_exists("monounsaturated-fat_100g", $data["nutriments"])){
                $res["fat_monoinsaturadas"] = $data["nutriments"]["monounsaturated-fat_100g"];
            }


            if(array_key_exists("trans-fat_100g", $data["nutriments"])){
                $res["fat_trans"] = $data["nutriments"]["trans-fat_100g"];
            } 

            if(array_key_exists("cholesterol_100g", $data["nutriments"])){
                $res["colesterol"] = $data["nutriments"]["cholesterol_100g"];
            }

            if(array_key_exists("sodium_100g", $data["nutriments"])){
                $res["sodio"] = $data["nutriments"]["sodium_100g"];
            }

            if(array_key_exists("fiber_100g", $data["nutriments"])){
                $res["fibra"] = $data["nutriments"]["fiber_100g"];
            }

            if(array_key_exists("potassium_100g", $data["nutriments"])){
                $res["potasio"] = $data["nutriments"]["potassium_100g"];
            } 

            if(array_key_exists("salt_100g", $data["nutriments"])){
                $res["sal"] = $data["nutriments"]["salt_100g"];
            } 
        }

        
        return $res;
    }
}