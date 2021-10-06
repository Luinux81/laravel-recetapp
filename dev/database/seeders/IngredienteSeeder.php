<?php

namespace Database\Seeders;

use App\Models\Ingrediente;
use Illuminate\Database\Seeder;

class IngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factoria = Ingrediente::factory();
        //$num = count($factoria->ingredientes);
        $num = 5;

        $ingredientes = $factoria->count($num)->make();

        foreach ($ingredientes as $ingrediente){
            $ingrediente->save();
        }
    }
}
