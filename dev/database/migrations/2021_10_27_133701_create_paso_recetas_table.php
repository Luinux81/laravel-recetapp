<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasoRecetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasos_receta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("receta_id");
            $table->foreign("receta_id")->references("id")->on("recetas");

            $table->integer("orden");
            $table->string("texto");
            $table->string("media_assets")->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pasos_receta');
    }
}
