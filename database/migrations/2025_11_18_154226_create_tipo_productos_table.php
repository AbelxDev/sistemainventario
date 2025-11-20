<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoProductosTable extends Migration
{
    public function up()
    {
        Schema::create('tipo_productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('prefijo', 10)->unique(); // MED, INS, EQU
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_productos');
    }
}
