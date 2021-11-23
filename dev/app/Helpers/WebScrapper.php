<?php

namespace App\Helpers;

use stdClass;
use DOMDocument;
use Goutte\Client;

class WebScrapper{

    static $fat_url = "https://www.fatsecret.es";

    public static function getAllCategorias(){        
        return WebScrapper::getCategoriasNivelUnoIngredientesFatSecret();
    }

    private static function getCategoriasNivelUnoIngredientesFatSecret(){
        $client = new Client();
        $crawler = $client->request('GET',WebScrapper::$fat_url . "/calor%C3%ADas-nutrici%C3%B3n/");

        $categorias = [];

        $crawler
            ->filter('table.generic.common a.prominent')
            ->each(function ($node) use (&$categorias) {
                $aux = new stdClass();
                
                $aux->nombre = $node->text();
                $aux->url = WebScrapper::$fat_url . $node->attr('href');
                $aux->subcategorias = WebScrapper::getSubcategoriasFatSecret($aux->url);
                
                $categorias[$node->text()] = $aux;
            });
        
        return $categorias;
    }

    private static function getSubcategoriasFatSecret($url){
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $categorias = [];

        $crawler
            ->filter('table.generic div.secHolder > h2 > a')
            ->each( function ($node) use (&$categorias){
                $categorias[$node->text()] = WebScrapper::$fat_url . $node->attr('href');
            });

        return $categorias;
    }

    public static function getIngredientesPorCategoriaFatSecret($url){
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $ingredientes = [];

        $crawler
            ->filter('table.generic div.secHolder')
            ->each( function($node) use (&$ingredientes){
                $ingredientes = WebScrapper::extraeIngredientes($node->html());
            });

        return $ingredientes;
    }

    private static function extraeIngredientes($html){
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        
        $tags = $dom->getElementsByTagName('*');
        $h2 = $dom->getElementsByTagName('h2');
        $out = [];

        for($i=0;$i<count($tags);$i++){
            if($tags[$i]->tagName == "h2"){
                $obj = new stdClass();
                    $obj->categoria = $tags[$i]->textContent;                    
                    $obj->listaIngredientes = WebScrapper::parseaIngredientes($tags,$i+2);
                
                array_push($out,$obj);
            }
        }       
        
        return $out;
    }

    

    private static function parseaIngredientes(&$tags,$indice){
        if(empty($tags->item($indice))) return null;

        if($tags[$indice]->getAttribute("class") != "food_links") return null;

        $ingredientes = [];
        
        $element = $tags->item($indice);
        $doc = $element->ownerDocument;

        $html = "";
        foreach ($element->childNodes as $node) {
            $html .= $doc->saveHTML($node);
        }

        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');


        foreach ($links as $link) {
            $obj = new stdClass();

            $obj->url = WebScrapper::$fat_url . $link->getAttribute('href');
            $obj->nombre = $link->textContent;
            
            array_push($ingredientes,$obj);
        }
        
        return $ingredientes;
    }

    public static function getIngredienteFatSecret($url){        
        $client = new Client();
        $crawler = $client->request('GET', $url);

        $facts = $crawler->filter('div.nutrition_facts')->first()->html();

        $dom = new DOMDocument();

        $dom->loadHTML($facts);
        $nodeList = $dom->getElementsByTagName('*');  
        
        $porcion = $nodeList->item(5)->textContent;
        
        if(strtolower($porcion) != "100 g"){            
            $link = $crawler->selectLink('100 g')->link();
            if(!empty($link)){
                $url = $link->getUri();
                $crawler = $client->request('GET', $url);
            }
        }

        $facts = $crawler->filter('div.nutrition_facts')->first()->html();
        
        $dom->loadHTML($facts);
        $nodeList = $dom->getElementsByTagName('*');  

        $obj = new stdClass();
            $obj->url                 = $url;
            $obj->calorias            = WebScrapper::extraerTextoItem($nodeList,15);
            $obj->fat_total           = WebScrapper::extraerTextoItem($nodeList,19);
            $obj->fat_saturadas       = WebScrapper::extraerTextoItem($nodeList,23);
            $obj->fat_monoinsaturadas = WebScrapper::extraerTextoItem($nodeList,27);
            $obj->fat_poliinsaturadas = WebScrapper::extraerTextoItem($nodeList,31);
            $obj->carb_total          = WebScrapper::extraerTextoItem($nodeList,35);
            $obj->carb_azucar         = WebScrapper::extraerTextoItem($nodeList,39);
            $obj->fibra               = WebScrapper::extraerTextoItem($nodeList,43);
            $obj->proteina            = WebScrapper::extraerTextoItem($nodeList,47);
            $obj->colesterol          = WebScrapper::extraerTextoItem($nodeList,55);
            $obj->potasio             = WebScrapper::extraerTextoItem($nodeList,59);
            
        return WebScrapper::formateaObjetoInfoNutricional($obj);
    }

    private static function extraerTextoItem(&$nodeList,$index){
        if (empty($nodeList->item($index))){
            return 0;
        }
        else{
            return $nodeList->item($index)->textContent;
        }
    }

    private static function formateaObjetoInfoNutricional($obj){
        $obj->calorias            = WebScrapper::formateaValorInfoNutricional($obj->calorias);
        $obj->fat_total           = WebScrapper::formateaValorInfoNutricional($obj->fat_total);
        $obj->fat_saturadas       = WebScrapper::formateaValorInfoNutricional($obj->fat_saturadas);
        $obj->fat_monoinsaturadas = WebScrapper::formateaValorInfoNutricional($obj->fat_monoinsaturadas);
        $obj->fat_poliinsaturadas = WebScrapper::formateaValorInfoNutricional($obj->fat_poliinsaturadas);
        $obj->carb_total          = WebScrapper::formateaValorInfoNutricional($obj->carb_total);
        $obj->carb_azucar         = WebScrapper::formateaValorInfoNutricional($obj->carb_azucar);
        $obj->fibra               = WebScrapper::formateaValorInfoNutricional($obj->fibra);
        $obj->proteina            = WebScrapper::formateaValorInfoNutricional($obj->proteina);
        $obj->colesterol          = WebScrapper::formateaValorInfoNutricional($obj->colesterol);
        $obj->potasio             = WebScrapper::formateaValorInfoNutricional($obj->potasio);

        return $obj;
    }

    private static function formateaValorInfoNutricional($valor){
        
        if(empty($valor)) return 0;
        
        $out = [];
        $decimal = false;

        for ($i = 0; $i < strlen($valor); $i++){
            if(is_numeric($valor[$i])){
                array_push($out, $valor[$i]);
            }

            if($valor[$i] == ','){
                array_push($out, $valor[$i]);
                $decimal = true;
            }
        }

        $res = implode('',$out);

        if($decimal){
            $res = str_replace(",",".",$res);
            $res = strval(round($res, 2));
            // $res = str_replace(".",",",$res);
        }

        return $res;
    }

}

?>