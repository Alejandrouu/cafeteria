{{-- resources/views/productos/show.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles del Producto</h1>
        <div class="card">
            <div class="card-body">
                {{-- Eliminé la sección de imagen --}}
                <h5 class="card-title">{{ $producto->nombre }}</h5>
                <p class="card-text"><strong>Descripción:</strong> {{ $producto->descripcion ?? 'No hay descripción disponible.' }}</p>
                <p class="card-text"><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>
                <p class="card-text"><strong>Stock:</strong> {{ $producto->stock }}</p>
                <p class="card-text"><strong>Creado el:</strong> {{ $producto->created_at }}</p>
                <p class="card-text"><strong>Última actualización:</strong> {{ $producto->updated_at }}</p>
                <a href="{{ route('productos.index') }}" class="btn btn-primary">Volver a la Lista</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
