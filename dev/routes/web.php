<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngredienteController;
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

    Route::get('/ingredientes/categoria',[CategoriaIngredienteController::class, 'index'])->name('ingredientes.categoria.index');
    Route::get('/ingredientes/categoria/create',[CategoriaIngredienteController::class, 'create'])->name('ingredientes.categoria.create');
    Route::post('/ingredientes/categoria',[CategoriaIngredienteController::class, 'store'])->name('ingredientes.categoria.store');
    Route::get('/ingredientes/categoria/{categoria}/edit',[CategoriaIngredienteController::class, 'edit'])->name('ingredientes.categoria.edit');
    Route::put('/ingredientes/categoria/{categoria}',[CategoriaIngredienteController::class, 'update'])->name('ingredientes.categoria.update');
});

// Hay que poner esta ruta por un tema con los guard al crear un rol o permiso nuevo
Route::view('permisos','permisos')->name('permisos')->middleware('auth');