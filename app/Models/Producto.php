<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Asegúrate de que 'stock' esté en esta lista
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock',];

    public function detallesPedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }
}