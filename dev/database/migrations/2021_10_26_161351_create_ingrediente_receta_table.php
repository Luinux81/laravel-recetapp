<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredienteRecetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingrediente_receta', function (Blueprint $table) {
            $table->unsignedBigInteger("ingrediente_id");
            $table->foreign("ingrediente_id")->references("id")->on("ingredientes");
            
            $table->unsignedBigInteger("receta_id");
            $table->foreign("receta_id")->references("id")->on("recetas");

            $table->primary(["ingrediente_id", "receta_id"]);

            $table->integer("cantidad");
            $table->string("unidad_medida");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingrediente_receta');
    }
}
