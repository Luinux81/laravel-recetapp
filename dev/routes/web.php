<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\PasoRecetaController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\CategoriaRecetaController;
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

    Route::get('/ingredientes',[IngredienteController::class, 'index'])->name('ingredientes');
    Route::get('/ingredientes/create',[IngredienteController::class, 'create'])->name('ingredientes.create');
    Route::post('/ingredientes',[IngredienteController::class, 'store'])->name('ingredientes.store');
    Route::get('/ingredientes/{ingrediente}/edit',[IngredienteController::class, 'edit'])->name('ingredientes.edit');
    Route::put('/ingredientes/{ingrediente}', [IngredienteController::class, 'update'])->name('ingredientes.update');
    Route::delete('/ingrediente/{ingrediente}', [IngredienteController::class, 'destroy'])->name('ingredientes.destroy');


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

    // Route::resource('recetas.categoria', CategoriaRecetaController::class);

    Route::resource('recetas', RecetaController::class);

    Route::get('recetas/{receta}/ingrediente/create', [RecetaController::class, 'createIngrediente'])->name('recetas.ingrediente.create');
    Route::post('recetas/{receta}/ingrediente/store', [RecetaController::class, 'storeIngrediente'])->name('recetas.ingrediente.store');
    Route::get('recetas/{receta}/ingrediente/{ingrediente}/edit', [RecetaController::class, 'editIngrediente'])->name('recetas.ingrediente.edit');
    Route::put('recetas/{receta}/ingrediente/{ingrediente}', [RecetaController::class, 'updateIngrediente'])->name('recetas.ingrediente.update');
    Route::delete('recetas/{receta}/ingrediente/{ingrediente}', [RecetaController::class, 'destroyIngrediente'])->name('recetas.ingrediente.destroy');

    Route::resource('recetas.paso', PasoRecetaController::class);

});

// Hay que poner esta ruta por un tema con los guard al crear un rol o permiso nuevo
Route::view('permisos','permisos')->name('permisos')->middleware('auth');