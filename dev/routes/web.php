<?php

use App\Http\Controllers\Web\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\Web\RecetaController;
use App\Http\Controllers\Web\PasoRecetaController;
use App\Http\Controllers\Web\IngredienteController;
use App\Http\Controllers\Web\IngredienteRecetaController;
use App\Http\Controllers\Web\CategoriaIngredienteController;
use App\Http\Controllers\Web\CategoriaRecetaController;
use App\Http\Controllers\SocialController;

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


Route::get('auth/facebook', [SocialController::class, 'facebookRedirect']);
Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook']);


Route::middleware(['auth:sanctum', 'verified'])->group(function(){
    Route::view('/dashboard','dashboard')->name('dashboard');

    // Sección admin

    Route::prefix('admin')->name('admin.')->group(function(){
        Route::prefix('seed')->name('seed.')->group(function(){
            Route::post('receta/{receta}',[RecetaController::class, 'saveSeed'])->name('receta');
        });
        
        Route::get('/review-published-queue',[AdminController::class,'reviewPublishedQueue'])->name('review-publish-queue');
        Route::get('/review-publish/{id}', [AdminController::class, 'reviewPublish'])->name('review-publish');
        Route::post('/confirm-publish/{id}',[AdminController::class,'confirmPublish'])->name('confirm-publish');
        Route::post('/deny-publish/{id}',[AdminController::class,'denyPublish'])->name('deny-publish');
    });
    

    // Ingredientes y categorias de ingrediente

    Route::prefix('ingredientes')->name('ingredientes.')->group(function(){
        Route::resource('categoria', CategoriaIngredienteController::class)->parameters(['categoria'=>'categoria']);

        Route::post('publish/{ingrediente}', [IngredienteController::class, 'publish'])->name('publish');
        Route::post('offsearch', [IngredienteController::class, 'offsearch'])->name('offsearch');
    });

    Route::resource('ingredientes', IngredienteController::class);



    // Recetas, categorias de receta, ingredientes y pasos de recetas

    Route::prefix('recetas')->name('recetas.')->group(function(){
        Route::resource('categoria', CategoriaRecetaController::class)->parameters(['categoria'=>'categoria']);
        
        Route::post('publish/{receta}', [RecetaController::class, 'publish'])->name('publish');

        Route::group(['prefix'=>"{receta}"], function(){
            Route::prefix("pasos/{paso}")
                    ->name('pasos.')
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