<?php

namespace App\Models;

use App\Models\Receta;
use App\Models\Ingrediente;
use App\Models\CategoriaReceta;
use Laravel\Sanctum\HasApiTokens;
use App\Models\CategoriaIngrediente;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function ingredientes()
    {
        return $this->hasMany(Ingrediente::class);
    }

    public function categoriasIngrediente()
    {
        return $this->hasMany(CategoriaIngrediente::class);
    }

    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }

    public function categoriasReceta()
    {
        return $this->hasMany(CategoriaReceta::class);
    }

    public function getAllIngredientes()
    {
        /** @var User */
        $user = auth()->user();
        
        $publicos       = Ingrediente::where('publicado',1)->whereNull('user_id')->withTrashed();
        $publicosUsers  = Ingrediente::where('publicado',1)->where('user_id', '!=', $user->id)->withTrashed();
        $publicoPropio  = Ingrediente::where('publicado',1)->where('user_id', $user->id)->withTrashed();

        $ingredientesPublicos = $publicos
                                    ->union($publicosUsers)
                                    ->union($publicoPropio)
                                    ;


        $ingredientes = $ingredientesPublicos
                            ->union($user->ingredientes())
                            ->orderBy('nombre')
                            ->get();

        return $ingredientes;
    }


    public function getAllRecetas()
    {
        /** @var User */
        $user = auth()->user();

        $publicos       = Receta::where('publicado', 1)->whereNull('user_id')->withTrashed();
        $publicosUsers  = Receta::where('publicado',1)->where('user_id', '!=', $user->id)->withTrashed();
        $publicoPropio  = Receta::where('publicado',1)->where('user_id', $user->id)->withTrashed();

        $recetasPublicas = $publicos
                                ->union($publicosUsers)
                                ->union($publicoPropio)
                                ;
        $recetas = $recetasPublicas
                        ->union($user->recetas())
                        ->orderBy('nombre')
                        ->get();

        return $recetas;
    }


    public function getAllCategoriasIngrediente()
    {
        /** @var User */
        $user = auth()->user();

        $publicos       = CategoriaIngrediente::where('publicado', 1)->whereNull('user_id')->withTrashed();
        $publicosUsers  = CategoriaIngrediente::where('publicado',1)->where('user_id', '!=', $user->id)->withTrashed();
        $publicoPropio  = CategoriaIngrediente::where('publicado',1)->where('user_id', $user->id)->withTrashed();

        $catIngredientesPublicos = $publicos
                                ->union($publicosUsers)
                                ->union($publicoPropio)
                                ;

        $categorias = $catIngredientesPublicos
                        ->union($user->categoriasIngrediente())
                        ->orderBy('nombre')
                        ->get();

        return $categorias;
    }


    public function getAllCategoriasReceta()
    {
        /** @var User */
        $user = auth()->user();

        $publicos       = CategoriaReceta::where('publicado', 1)->whereNull('user_id')->withTrashed();
        $publicosUsers  = CategoriaReceta::where('publicado', 1)->where('user_id', '!=', $user->id)->withTrashed();
        $publicoPropio  = CategoriaReceta::where('publicado', 1)->where('user_id', $user->id)->withTrashed();

        $catRecetasPublicas = $publicos
                                ->union($publicosUsers)
                                ->union($publicoPropio)
                                ;

        $categorias = $catRecetasPublicas
                        ->union($user->categoriasReceta())
                        ->orderBy('nombre')
                        ->get();

        return $categorias;
    }
}
