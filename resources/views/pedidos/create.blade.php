<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nuevo Pedido</title>
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
        <h1>Registrar Nuevo Pedido</h1>

        {{-- Mostrar errores de validación si existen --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Mostrar un mensaje de error general si existe en la sesión --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('pedidos.store') }}" method="POST">
            @csrf {{-- Directiva de seguridad de Laravel para formularios --}}

            {{-- Campo para seleccionar el cliente --}}
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Cliente:</label>
                <select class="form-select" id="cliente_id" name="cliente_id" required>
                    <option value="">Seleccione un cliente</option>
                    {{-- Iterar sobre la lista de clientes proporcionada por el controlador --}}
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nombre }} ({{ $cliente->correo }})
                        </option>
                    @endforeach
                </select>
            </div>

            <hr>
            <h2>Productos del Pedido</h2>
            <div id="productos-container">
                {{-- Si hay productos antiguos (por ejemplo, después de un error de validación), volver a cargarlos --}}
                @if(old('productos'))
                    @foreach(old('productos') as $index => $oldProduct)
                        <div class="row mb-3 product-item">
                            <div class="col-md-6">
                                <label for="productos_{{ $index }}_id" class="form-label">Producto:</label>
                                <select class="form-select product-select" name="productos[{{ $index }}][id]" data-index="{{ $index }}" required>
                                    <option value="">Seleccione un producto</option>
                                    {{-- Iterar sobre la lista de productos --}}
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}" data-price="{{ $producto->precio }}" data-stock="{{ $producto->stock }}" {{ $oldProduct['id'] == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }} (Stock: {{ $producto->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="productos_{{ $index }}_cantidad" class="form-label">Cantidad:</label>
                                <input type="number" class="form-control product-cantidad" name="productos[{{ $index }}][cantidad]" value="{{ $oldProduct['cantidad'] }}" min="1" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-product-btn">Eliminar</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-product-btn">Añadir Otro Producto</button>

            <h3 class="mt-4">Total del Pedido: <span id="total-pedido">$0.00</span></h3>

            <button type="submit" class="btn btn-success mt-3">Guardar Pedido</button>
            <a href="{{ route('pedidos.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
        </form>
    </div>

    {{-- Scripts de Bootstrap y nuestro JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializa el índice para los nuevos productos. Si hay productos viejos, continúa desde su conteo.
        let productIndex = {{ old('productos') ? count(old('productos')) : 0 }};

        // Event listener para el botón "Añadir Otro Producto"
        document.getElementById('add-product-btn').addEventListener('click', function() {
            addProductField();
        });

        // Event listener delegado para cambios en los select y inputs de cantidad dentro del contenedor de productos
        document.getElementById('productos-container').addEventListener('change', function(event) {
            if (event.target.classList.contains('product-select') || event.target.classList.contains('product-cantidad')) {
                calculateTotal(); // Recalcular total cuando cambia un producto o su cantidad
            }
        });

        // Event listener delegado para el botón "Eliminar" de cada producto
        document.getElementById('productos-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-product-btn')) {
                event.target.closest('.product-item').remove(); // Eliminar el elemento padre del producto
                calculateTotal(); // Recalcular total después de eliminar
            }
        });

        /**
         * Añade un nuevo campo de producto al formulario.
         * @param {string} productId - ID del producto a seleccionar (opcional, para precargar).
         * @param {number} cantidad - Cantidad del producto (opcional, para precargar).
         */
        function addProductField(productId = '', cantidad = 1) {
            const container = document.getElementById('productos-container');
            const newProductDiv = document.createElement('div');
            newProductDiv.classList.add('row', 'mb-3', 'product-item'); // Clases para estilos y selector

            // HTML para el nuevo campo de producto
            newProductDiv.innerHTML = `
                <div class="col-md-6">
                    <label for="productos_${productIndex}_id" class="form-label">Producto:</label>
                    <select class="form-select product-select" name="productos[${productIndex}][id]" data-index="${productIndex}" required>
                        <option value="">Seleccione un producto</option>
                        {{-- Se inyecta la lista de productos de Laravel aquí. TEN CUIDADO con comillas simples si hay nombres con apóstrofes. --}}
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" data-price="{{ $producto->precio }}" data-stock="{{ $producto->stock }}" ${productId == {{ $producto->id }} ? 'selected' : ''}>
                                {{ $producto->nombre }} - ${{ number_format($producto->precio, 2) }} (Stock: {{ $producto->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="productos_${productIndex}_cantidad" class="form-label">Cantidad:</label>
                    <input type="number" class="form-control product-cantidad" name="productos[${productIndex}][cantidad]" value="${cantidad}" min="1" required>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-product-btn">Eliminar</button>
                </div>
            `;
            container.appendChild(newProductDiv);
            productIndex++; // Incrementa el índice para el próximo producto
            calculateTotal(); // Recalcular el total al añadir un nuevo campo
        }

        /**
         * Calcula y actualiza el total del pedido sumando los precios de los productos y sus cantidades.
         */
        function calculateTotal() {
            let total = 0;
            const productItems = document.querySelectorAll('.product-item'); // Obtener todos los elementos de producto

            productItems.forEach(item => {
                const select = item.querySelector('.product-select');
                const cantidadInput = item.querySelector('.product-cantidad');

                // Asegurarse de que ambos elementos existen y tienen valores
                if (select && select.value && cantidadInput && cantidadInput.value) {
                    const selectedOption = select.options[select.selectedIndex];
                    const price = parseFloat(selectedOption.dataset.price); // Obtener precio del atributo data-price
                    const cantidad = parseInt(cantidadInput.value); // Obtener cantidad

                    // Validar que los valores sean números válidos y la cantidad sea positiva
                    if (!isNaN(price) && !isNaN(cantidad) && cantidad > 0) {
                        total += price * cantidad;
                    }
                }
            });

            // Actualizar el texto del total del pedido
            document.getElementById('total-pedido').innerText = '$' + total.toFixed(2);
        }

        // Llamar a calculateTotal() al cargar la página para reflejar los productos precargados por old()
        calculateTotal();
    </script>
</body>
</html>