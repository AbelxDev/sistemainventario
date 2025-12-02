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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            // FK al producto
            $table->unsignedBigInteger('producto_id');

            // FK al detalle de factura
            $table->unsignedBigInteger('factura_detalle_id');

            // Datos del lote (FIFO)
            $table->integer('cantidad_ingresada');   // Cantidad total del lote
            $table->integer('cantidad_restante');    // Cantidad restante para FIFO
            $table->date('fecha_ingreso');           // Orden para FIFO

            $table->timestamps();

            // Relaciones
            $table->foreign('producto_id')
                ->references('id')->on('productos')
                ->onDelete('restrict');

            $table->foreign('factura_detalle_id')
                ->references('id')->on('factura_detalles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
