<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredientes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('cat_id')->nullable();
            $table->foreign('cat_id')->references('id')->on('categorias_ingrediente');
            
            $table->string("nombre");
            $table->string("descripcion")->nullable();
            $table->string("marca")->nullable();
            $table->string("barcode")->nullable();
            $table->string("imagen")->nullable();
            $table->string("url")->nullable();

            $table->integer("calorias")->nullable();
            $table->decimal("fat_total",5,2)->nullable();
            $table->decimal("fat_saturadas",5,2)->nullable();
            $table->decimal("fat_poliinsaturadas",5,2)->nullable();
            $table->decimal("fat_monoinsaturadas",5,2)->nullable();
            $table->decimal("fat_trans",5,2)->nullable();
            $table->decimal("colesterol",5,2)->nullable();
            $table->decimal("sodio",6,2)->nullable();
            $table->decimal("potasio",6,2)->nullable();
            $table->decimal("fibra",5,2)->nullable();
            $table->decimal("carb_total",5,2)->nullable();
            $table->decimal("carb_azucar",5,2)->nullable();
            $table->decimal("proteina",5,2)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredientes');
    }
}
