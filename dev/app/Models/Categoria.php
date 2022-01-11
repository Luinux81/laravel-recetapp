<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class Categoria extends Model
{
    use HasFactory;

    abstract protected function elementos();
    abstract protected function padre();

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function esPublico() : bool
    {
        if($this->publicado == NULL){
            $this->publicado = false;
        }

        return $this->publicado;
    }

    public function esVacia() : bool
    {
        return ($this->elementos()->count() == 0);
    }

    public function esHoja() : bool
    {
        return ($this->hijos()->count() == 0);
    }


    public function esRaiz() : bool
    {
        return ($this->catParent_id == NULL);
    }



    public function hijos(bool $recursivo = false) : Collection
    {
        $out = new Collection();
        $class = get_class($this);
        
        $hijos = $class::where('catParent_id','=',$this->id)->get();        
        $visitados = collect();        
        $i = 0;

        if($recursivo){            
            while($i < $hijos->count()){
                $cat = $hijos->all()[$i];
                if (!$visitados->contains($cat)){
                    $visitados->add($cat);

                    $subHijos = $class::where('catParent_id','=',$cat->id)->get();
                    foreach ($subHijos as $key => $subHijo) {
                        $hijos->add($subHijo);
                    }
                }
                $i++;
            }  
            $out = $hijos;      
        }
        else{
            $out = $hijos;
        }

        return $out;
    }


    public function borradoCompleto() : void
    {
        $hijas = $this->hijos(true);

        foreach ($hijas as $cat) {
            $cat->update([
                "catParent_id" => NULL,
            ]);
        }
        
        if($this->esPublico()){
            // TODO: Verificar que esto es lo que se quiere, poder borrar del todo una categoria pública
            if($this->esVacia() && $this->esHoja()){
                $this->forceDelete();
            }
            else{
                $this->delete();
            }
        }
        else{
            $this->forceDelete();
        }
    }


    public static function vacias(string $modelo = "ingrediente") 
    {
        switch ($modelo){
            case "ingrediente":
                $cat_tabla = "categorias_ingrediente";
                $res_tabla = "ingredientes";
                break;
            default:
                $cat_tabla = "categorias_receta";
                $res_tabla = "recetas";
                break;
        }

        $res = DB::table($cat_tabla)
                    ->leftJoin($res_tabla, $cat_tabla . '.id', '=', $res_tabla . '.cat_id')
                    ->selectRaw($cat_tabla . '.id, ' . $cat_tabla . '.nombre, count(' . $res_tabla . '.id) as num_elementos')
                    ->groupBy($cat_tabla . '.id', $cat_tabla . '.nombre')
                    ->having('num_elementos', '=', 0)
                    ->get();

        dd($res);
    }



    public static function arbol(Collection $categorias)
    {
        $resultado = collect();
        $recorridos = collect();

        Categoria::_arbol($categorias,$recorridos,$resultado);

        return $resultado;
    }


    private static function _arbol(Collection $categorias, Collection &$recorridos = NULL, Collection &$resultado)
    {
        if($recorridos == NULL){
            $recorridos = collect();            
        }

        if($resultado == NULL){
            $resultado = collect();
        }

        $categorias = $categorias->sortBy([
            ['catParent_id','asc']
        ]);

        foreach ($categorias as $categoria) {
            if(!$resultado->contains($categoria)){
                $recorridos->add($categoria);
                $resultado->add($categoria);

                if(!$categoria->esHoja()){
                    Categoria::_arbol($categoria->hijos(), $recorridos, $resultado);
                }
            }
        }
    }
}
