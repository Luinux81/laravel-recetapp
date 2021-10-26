<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("recetas")->insert([
            "user_id"=>1,
            "nombre"=>"Arroz blanco",
            "descripcion"=>"Arroz cocido sin nada más",
            "calorias"=>"500",
        ]);
    }
}
