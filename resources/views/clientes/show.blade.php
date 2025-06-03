<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
        <a class="navbar-brand" href="/">Mi Cafetería</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Menú</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('productos.index') }}">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clientes.index') }}">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pedidos.index') }}">Pedidos</a> </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1>Detalles del Cliente</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $cliente->nombre }}</h5>
                <p class="card-text"><strong>Correo:</strong> {{ $cliente->correo }}</p>
                <p class="card-text"><strong>Teléfono:</strong> {{ $cliente->telefono ?? 'N/A' }}</p>
                <p class="card-text"><strong>Registrado el:</strong> {{ $cliente->created_at }}</p>
                <p class="card-text"><strong>Última actualización:</strong> {{ $cliente->updated_at }}</p>
                <a href="{{ route('clientes.index') }}" class="btn btn-primary">Volver a la Lista</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>