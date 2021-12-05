<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CategoriaIngrediente;

class IngredienteRecetaSeeder extends Seeder
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
        $path = "storage/seeds/ingrediente_receta.sql";
        
        DB::unprepared(file_get_contents($path));

        $this->command->info('IngredientesRecetas table seeded!');
    }
}
