<?php

namespace App\Models;

use App\Models\User;
use App\Models\Ingrediente;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CategoriaIngrediente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "categorias_ingrediente";

    protected $guarded = [];

    public function ingredientes()
    {
        return $this->hasMany(Ingrediente::class, 'cat_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoriaPadre()
    {
        return $this->belongsTo(CategoriaIngrediente::class);
    }

    public function categoriasHija($recursivo = false) : Collection
    {
        $out = new Collection();
        
        $hijas = CategoriaIngrediente::where('catParent_id','=',$this->id)->get();        
        $visitados = collect();        
        $i = 0;

        if($recursivo){            
            while($i < $hijas->count()){
                $cat = $hijas->all()[$i];
                if (!$visitados->contains($cat)){
                    $visitados->add($cat);

                    $subHijas = CategoriaIngrediente::where('catParent_id','=',$cat->id)->get();
                    foreach ($subHijas as $key => $subHija) {
                        $hijas->add($subHija);
                    }
                }
                $i++;
            }  
            $out = $hijas;      
        }
        else{
            $out = $hijas;
        }

        return $out;
    }

    public function categoriaRaiz() : bool
    {
        return $this->catParent_id == NULL;
    }


    public function borradoCompleto() : void
    {
        $hijas = $this->categoriasHija(true);

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



    public function esPublico() : bool
    {
        if($this->publicado == NULL){
            $this->publicado = false;
        }

        return $this->publicado;
    }


    public function esVacia() : bool
    {
        return ($this->ingredientes()->count() == 0);
    }


    public function esHoja() : bool
    {
        return ($this->categoriasHija()->count() == 0);
    }


    public static function vacias() 
    {
        $res = DB::table('categorias_ingrediente')
                    ->leftJoin('ingredientes', 'categorias_ingrediente.id', '=', 'ingredientes.cat_id')
                    ->selectRaw('categorias_ingrediente.id, categorias_ingrediente.nombre, count(ingredientes.id) as num_ingredientes')
                    ->groupBy('categorias_ingrediente.id', 'categorias_ingrediente.nombre')
                    ->having('num_ingredientes', '=', 0)
                    ->get();

        dd($res);
    }

    public static function arbol(Collection $categorias)
    {
        $resultado = collect();
        $recorridos = collect();

        CategoriaIngrediente::_arbol($categorias,$recorridos,$resultado);

        return $resultado;
    }

    private static function _arbol(Collection $categorias, Collection &$recorridos = NULL, Collection &$resultado)
    {
        // TODO: Recorremos categorias, vamos añadiendo a res, si tiene hijos los pasamos recursivamente, si no devuelve un array con la categoria y luego añade a lo que llevemos

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
                    CategoriaIngrediente::_arbol($categoria->categoriasHija(), $recorridos, $resultado);
                }
            }
        }



        // // $resultado = collect();
        // $res = (object)["categoria" => NULL, "hijos" => NULL ];

        // foreach ($categorias as $categoria) {
        //     if(!$recorridos->contains($categoria->id)){
        //         $res->categoria = $categoria;
        //         $recorridos->add($categoria->id);
            
        //         if($categoria->esHoja()){   
        //             $resultado->add($res);
        //             return $resultado;
        //         }
        //         else{
        //             $hijas = $categoria->categoriasHija();
        //             foreach ($hijas as $hija) {
        //                 if(!$recorridos->contains($hija->id)){
        //                     $hijas->add(CategoriaIngrediente::arbol(collect()->add($hija), $recorridos, $resultado));
        //                 }
        //             }
        //             $res->hijos = $hijas;
        //         }
        //     }
        // }
        // $resultado->add($res);
        // return $resultado;
    }
}

