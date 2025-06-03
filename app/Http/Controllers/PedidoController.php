<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Cliente; // Necesitamos el modelo Cliente
use App\Models\Producto; // Necesitamos el modelo Producto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para transacciones de base de datos

class PedidoController extends Controller
{
    /**
     * Muestra una lista de todos los pedidos.
     */
    public function index()
    {
        // Carga los pedidos con sus clientes asociados y detalles de productos para mostrarlos
        $pedidos = Pedido::with('cliente', 'productos')->orderBy('created_at', 'desc')->get();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra el formulario para crear un nuevo pedido.
     */
    public function create()
    {
        $clientes = Cliente::all(); // Obtener todos los clientes para el select
        $productos = Producto::where('stock', '>', 0)->get(); // Obtener productos con stock > 0
        return view('pedidos.create', compact('clientes', 'productos'));
    }

    /**
     * Almacena un nuevo pedido en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::beginTransaction(); // Iniciar una transacción para asegurar la integridad de los datos

        try {
            $totalPedido = 0;
            $detallesPedido = [];

            foreach ($request->productos as $item) {
                $producto = Producto::find($item['id']);

                if (!$producto || $producto->stock < $item['cantidad']) {
                    DB::rollBack(); // Revertir la transacción si no hay stock suficiente
                    return redirect()->back()->withInput()->withErrors(['stock' => 'No hay suficiente stock para el producto ' . $producto->nombre]);
                }

                $subtotal = $producto->precio * $item['cantidad'];
                $totalPedido += $subtotal;

                // Preparar los datos para el detalle del pedido
                $detallesPedido[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio, // Registrar el precio actual del producto
                    'created_at' => now(), // Para los timestamps
                    'updated_at' => now(), // Para los timestamps
                ];

                // Decrementar el stock del producto
                $producto->stock -= $item['cantidad'];
                $producto->save();
            }

            // Crear el encabezado del pedido
            $pedido = Pedido::create([
                'cliente_id' => $request->cliente_id,
                'total' => $totalPedido,
            ]);

            // Guardar los detalles del pedido
            $pedido->detalles()->createMany($detallesPedido);

            DB::commit(); // Confirmar la transacción
            return redirect()->route('pedidos.index')->with('success', 'Pedido registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de cualquier error
            return redirect()->back()->withInput()->with('error', 'Error al registrar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los detalles de un pedido específico.
     */
    public function show(Pedido $pedido)
    {
        // Cargar las relaciones para mostrarlas en la vista
        $pedido->load('cliente', 'detalles.producto');
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Muestra el formulario para editar el pedido (no se implementa edición directa de ventas).
     */
    public function edit(Pedido $pedido)
    {
        // Redirige o devuelve un error, ya que no se espera editar pedidos directamente
        return redirect()->route('pedidos.index')->with('error', 'La edición de pedidos no está habilitada.');
    }

    /**
     * Actualiza el pedido en la base de datos (no se implementa edición directa de ventas).
     */
    public function update(Request $request, Pedido $pedido)
    {
        // Redirige o devuelve un error
        return redirect()->route('pedidos.index')->with('error', 'La actualización de pedidos no está habilitada.');
    }

    /**
     * Elimina un pedido de la base de datos (con rollback de stock).
     */
    public function destroy(Pedido $pedido)
    {
        DB::beginTransaction(); // Iniciar transacción para revertir stock

        try {
            // Revertir el stock de los productos antes de eliminar el pedido
            foreach ($pedido->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }

            $pedido->delete();
            DB::commit(); // Confirmar la transacción

            return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado exitosamente y stock revertido.');

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir en caso de error
            return redirect()->back()->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
        }
    }
}