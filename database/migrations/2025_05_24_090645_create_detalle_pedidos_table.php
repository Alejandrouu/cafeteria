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
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained()->onDelete('cascade'); // Clave foránea para el pedido
            $table->foreignId('producto_id')->constrained()->onDelete('cascade'); // Clave foránea para el producto
            $table->integer('cantidad'); // Cantidad de este producto en el pedido
            $table->decimal('precio_unitario', 8, 2); // precio del producto al momento de la compra
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_pedidos');
    }
};