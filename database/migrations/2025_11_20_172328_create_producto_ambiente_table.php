<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoAmbienteTable extends Migration
{
    public function up()
    {
        Schema::create('producto_ambiente', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('ambiente_id');
            $table->integer('cantidad')->default(0);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('producto_id')
                ->references('id')->on('productos')
                ->onDelete('cascade');

            $table->foreign('ambiente_id')
                ->references('id')->on('ambientes')
                ->onDelete('restrict');

            // Evitar duplicados (mismo producto en el mismo ambiente)
            $table->unique(['producto_id', 'ambiente_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_ambiente');
    }
}
