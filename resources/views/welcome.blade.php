<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menú de la Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6F4E37; /* Café tradicional */
            --secondary-color: #C4A484; /* Café claro */
            --accent-color: #E6B800; /* Dorado */
            --light-bg: #F9F5F0; /* Fondo claro café */
            --dark-text: #3E2723; /* Café oscuro para texto */
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-text);
            padding-top: 80px;
        }
        
        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
        }
        
        .navbar-brand, .nav-link {
            font-family: 'Playfair Display', serif;
            color: #f8f9fa !important;
            font-size: 1.5rem;
        }
        
        .nav-link:hover {
            color: var(--accent-color) !important;
        }
        
        .menu-section {
            padding: 40px 0;
        }
        
        .menu-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }
        
        .menu-title::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--accent-color);
        }
        
        .menu-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .menu-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
        }
        
        .item-details {
            flex-grow: 1;
        }
        
        .item-name {
            font-family: 'Playfair Display', serif;
            font-weight: bold;
            margin-bottom: 5px;
            color: var(--primary-color);
            font-size: 1.3rem;
        }
        
        .item-description {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .item-price {
            font-weight: bold;
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .view-details-btn {
            margin-left: 20px;
            background-color: var(--primary-color);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        
        .view-details-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-top: 40px;
        }
        
        @media (max-width: 768px) {
            .menu-title {
                font-size: 2rem;
            }
            
            .item-name {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body class="antialiased">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">Manos Artesanales</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('productos.index') }}">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container menu-section">
        <h2 class="menu-title">Nuestro Menú</h2>
        <div class="row">
            @if($productos->isEmpty())
                <div class="col-12 text-center">
                    <p>Nuestro menú está vacío por ahora. ¡Vuelve pronto!</p>
                </div>
            @else
                @foreach($productos as $producto)
                    <div class="col-md-6">
                        <div class="menu-item">
                            <div class="item-details">
                                <h5 class="item-name">{{ $producto->nombre }}</h5>
                                @if($producto->descripcion)
                                    <p class="item-description">{{ $producto->descripcion }}</p>
                                @endif
                                <p class="item-price">${{ number_format($producto->precio, 2) }}</p>
                            </div>
                            <a href="{{ route('productos.show', $producto->id) }}" class="btn btn-sm view-details-btn">Ver Detalles</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Mi Cafetería. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>