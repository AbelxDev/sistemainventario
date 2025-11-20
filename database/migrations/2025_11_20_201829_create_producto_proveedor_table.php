<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoProveedorTable extends Migration
{
    public function up()
    {
        Schema::create('producto_proveedor', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('proveedor_id');

            $table->timestamps();

            // FOREIGN KEYS
            $table->foreign('producto_id')
                ->references('id')->on('productos')
                ->onDelete('cascade');

            $table->foreign('proveedor_id')
                ->references('id')->on('proveedors') // ojo: tu tabla real
                ->onDelete('cascade');

            // Evitar que se repita un proveedor asignado a un producto
            $table->unique(['producto_id', 'proveedor_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_proveedor');
    }
}
