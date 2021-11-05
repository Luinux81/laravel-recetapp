<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaIngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Productos frescos",
            "descripcion"=>"Sólo productos frescos",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Fruta",
            "descripcion"=>"Frutas",
            "catParent_id"=>"1",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Verdura",
            "descripcion"=>"Verduras",
            "catParent_id"=>"1",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Carnes",
            "descripcion"=>"Carnes",
            "catParent_id"=>"1",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Pescados",
            "descripcion"=>"Pescados",
            "catParent_id"=>"1",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Productos preparados",
            "descripcion"=>"Sólo productos preparados",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Salsas",
            "descripcion"=>"Salsas",
            "catParent_id"=>"6",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Embutidos",
            "descripcion"=>"Embutidos",
            "catParent_id"=>"6",
        ]);

        DB::table("categorias_ingrediente")->insert([
            "user_id"=>"1",
            "nombre"=>"Cereales",
            "descripcion"=>"Cereales",
        ]);

    }
}
