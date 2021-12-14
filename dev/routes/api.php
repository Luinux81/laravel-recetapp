<?php

use App\Http\Controllers\Api\AssetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaIngredienteController;
use App\Http\Controllers\Api\CategoriaRecetaController;
use App\Http\Controllers\Api\RecetaController;
use App\Http\Controllers\Api\PasoRecetaController;
use App\Http\Controllers\Api\IngredienteController;
use App\Http\Controllers\Api\IngredienteRecetaController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('/logout',[AuthController::class, 'logout']);

    Route::prefix("v1")->name("v1.")->group(function(){        
        
        Route::prefix("ingredientes")
                ->name("ingredientes.")
                ->group(function(){
                    Route::apiResource("categorias",CategoriaIngredienteController::class);
                });

        Route::apiResource("ingredientes",IngredienteController::class);        


        Route::prefix("recetas")
                ->name("recetas.")
                ->group(function(){
                    Route::group([], function(){
                        Route::apiResource("categorias",CategoriaRecetaController::class);
                    });

                    Route::group(['prefix'=>'{receta}'],function(){
                        Route::prefix("pasos/{paso}")
                                ->name('pasos.')
                                ->group(function(){
                                    Route::apiResource("assets",AssetController::class)->only(['store','destroy']);
                                });


                        Route::apiResource("pasos",PasoRecetaController::class);
                        Route::apiResource("ingredientes",IngredienteRecetaController::class);
                    });            

                });

        Route::apiResource("recetas",RecetaController::class);
    });
    
});

