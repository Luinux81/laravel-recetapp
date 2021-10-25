<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaRecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("categorias_receta")->insert([
            "user_id"=>"1",
            "nombre"=>"Sopas",
            "descripcion"=>"Sopas",
        ]);

        DB::table("categorias_receta")->insert([
            "user_id"=>"1",
            "nombre"=>"Arroces",
            "descripcion"=>"Arroces",
        ]);

        DB::table("categorias_receta")->insert([
            "user_id"=>"1",
            "nombre"=>"Carnes",
            "descripcion"=>"Carnes",
        ]);

        DB::table("categorias_receta")->insert([
            "user_id"=>"1",
            "nombre"=>"Pescados",
            "descripcion"=>"Pescados",
        ]);

        DB::table("categorias_receta")->insert([
            "user_id"=>"1",
            "nombre"=>"Postres",
            "descripcion"=>"Postres",
        ]);
    }
}
