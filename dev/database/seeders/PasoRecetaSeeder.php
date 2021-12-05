<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PasoRecetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $this->seedAutoFile();
    }

    private function seedAutoFile(){
        $path = "storage/seeds/pasos_receta.sql";
        
        DB::unprepared(file_get_contents($path));

        $this->command->info('PasosRecetas table seeded!');
    }

    public function seedManual()
    {
        DB::table("pasos_receta")->insert([
            "receta_id" => 1,
            "orden" => 1,
            "texto" => "Ponemos agua a hervir a fuego medio con un poco de aceite y echamos el arroz."
        ]);

        DB::table("pasos_receta")->insert([
            "receta_id" => 1,
            "orden" => 2,
            "texto" => "Remover durante 20 minutos y colar."
        ]);
    }
}
