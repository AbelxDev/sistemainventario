<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();

            $table->string('numero')->unique();     // Ej: F001-000123
            $table->date('fecha');                 // Fecha de emisión

            $table->unsignedBigInteger('proveedor_id');

            $table->string('pdf_ruta')->nullable();   // Ruta del PDF en storage
            $table->string('estado')->default('pendiente'); // pendiente | procesada | anulada

            $table->timestamps();

            $table->foreign('proveedor_id')
                ->references('id')->on('proveedors') // OJO: tu tabla se llama proveedors
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
