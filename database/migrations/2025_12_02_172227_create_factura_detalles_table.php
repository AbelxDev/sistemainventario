<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factura_detalles', function (Blueprint $table) {
            $table->id();

            // FK a facturas
            $table->unsignedBigInteger('factura_id');

            // FK a productos
            $table->unsignedBigInteger('producto_id');

            // Cantidades
            $table->integer('cantidad');       // Cantidad solicitada
            $table->integer('recibidos')->default(0); // Cantidad realmente ingresada
            $table->integer('faltantes')->default(0); // Cantidad faltante

            $table->timestamps();

            // Relaciones
            $table->foreign('factura_id')
                ->references('id')->on('facturas')
                ->onDelete('cascade');

            $table->foreign('producto_id')
                ->references('id')->on('productos')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_detalles');
    }
};
