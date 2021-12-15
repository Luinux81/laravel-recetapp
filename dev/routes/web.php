<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\PasoRecetaController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\Web\IngredienteRecetaController;
use App\Http\Controllers\Web\CategoriaIngredienteController;
use App\Http\Controllers\Web\CategoriaRecetaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function(){
    Route::view('/dashboard','dashboard')->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function(){
        Route::prefix('seed')->name('seed.')->group(function(){
            Route::post('receta/{receta}',[RecetaController::class, 'saveSeed'])->name('receta');
        });
    });
    
    Route::prefix('ingredientes')->name('ingredientes.')->group(function(){
        Route::resource('categoria', CategoriaIngredienteController::class)->parameters(['categoria'=>'categoria']);
    });

    Route::resource('ingredientes', IngredienteController::class);

    Route::prefix('recetas')->name('recetas.')->group(function(){
        Route::resource('categoria', CategoriaRecetaController::class)->parameters(['categoria'=>'categoria']);

        Route::group(['prefix'=>"{receta}"], function(){
            Route::prefix("pasos/{paso}")
                    ->name('paso.')
                    ->group(function(){
                        Route::resource("asset",AssetController::class)->only(['store','destroy']);
                    });

            Route::resource('ingrediente', IngredienteRecetaController::class)->except(['index','show']);
            Route::resource('paso', PasoRecetaController::class)->except(['index','show']);            
        });
    });
    

    Route::resource('recetas', RecetaController::class);
});

// Hay que poner esta ruta por un tema con los guard al crear un rol o permiso nuevo
Route::view('permisos','permisos')->name('permisos')->middleware('auth');