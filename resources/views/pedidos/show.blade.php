<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
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
        <h1>Detalles del Pedido #{{ $pedido->id }}</h1>
        <div class="card mb-4">
            <div class="card-header">
                Información del Pedido
            </div>
            <div class="card-body">
                <p><strong>Cliente:</strong> {{ $pedido->cliente->nombre }} ({{ $pedido->cliente->correo }})</p>
                <p><strong>Fecha y Hora:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Total del Pedido:</strong> ${{ number_format($pedido->total, 2) }}</p>
            </div>
        </div>

        <h2>Productos en este Pedido:</h2>
        @if($pedido->detalles->isEmpty())
            <p>No hay productos en este pedido.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto->nombre }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                            <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>${{ number_format($pedido->total, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        @endif

        <a href="{{ route('pedidos.index') }}" class="btn btn-primary mt-3">Volver a la Lista de Pedidos</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>