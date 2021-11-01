<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\PasoRecetaController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\CategoriaRecetaController;
use App\Http\Controllers\IngredienteRecetaController;
use App\Http\Controllers\CategoriaIngredienteController;

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

    Route::resource('ingredientes', IngredienteController::class);
    // Route::resource('ingredientes.categoria', CategoriaIngredienteController::class)->shallow();
    
    Route::get('/ingredientes/categoria',[CategoriaIngredienteController::class, 'index'])->name('ingredientes.categoria.index');
    Route::get('/ingredientes/categoria/create',[CategoriaIngredienteController::class, 'create'])->name('ingredientes.categoria.create');
    Route::post('/ingredientes/categoria',[CategoriaIngredienteController::class, 'store'])->name('ingredientes.categoria.store');
    Route::get('/ingredientes/categoria/{categoria}/edit',[CategoriaIngredienteController::class, 'edit'])->name('ingredientes.categoria.edit');
    Route::put('/ingredientes/categoria/{categoria}',[CategoriaIngredienteController::class, 'update'])->name('ingredientes.categoria.update');
    Route::delete('/ingredientes/categoria/{categoria}',[CategoriaIngredienteController::class, 'destroy'])->name('ingredientes.categoria.destroy');

    Route::get('/recetas/categoria',[CategoriaRecetaController::class, 'index'])->name('recetas.categoria.index');
    Route::get('/recetas/categoria/create',[CategoriaRecetaController::class, 'create'])->name('recetas.categoria.create');
    Route::post('/recetas/categoria',[CategoriaRecetaController::class, 'store'])->name('recetas.categoria.store');
    Route::get('/recetas/categoria/{categoria}/edit',[CategoriaRecetaController::class, 'edit'])->name('recetas.categoria.edit');
    Route::put('/recetas/categoria/{categoria}',[CategoriaRecetaController::class, 'update'])->name('recetas.categoria.update');
    Route::delete('/recetas/categoria/{categoria}',[CategoriaRecetaController::class, 'destroy'])->name('recetas.categoria.destroy');


    Route::resource('recetas', RecetaController::class);
    Route::resource('recetas.ingrediente', IngredienteRecetaController::class);
    Route::resource('recetas.paso', PasoRecetaController::class);

    Route::post('recetas/{receta}/paso/{paso}/asset', [AssetController::class, 'store'])->name('recetas.paso.asset.store');
    Route::delete('recetas/{receta}/paso/{paso}/asset/{asset}', [AssetController::class, 'destroy'])->name('recetas.paso.asset.destroy');

});

// Hay que poner esta ruta por un tema con los guard al crear un rol o permiso nuevo
Route::view('permisos','permisos')->name('permisos')->middleware('auth');