<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'total',
    ];

    // Relación: Un Pedido pertenece a un Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: Un Pedido tiene muchos DetallePedido
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    // Relación (a través de detalles): Un Pedido tiene muchos Productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_pedidos')
                    ->withPivot('cantidad', 'precio_unitario')
                    ->withTimestamps();
    }
}