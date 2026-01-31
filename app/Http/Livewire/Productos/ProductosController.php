<?php

namespace App\Http\Livewire\Productos;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductosController extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $pageTitle, $componentName;
    private $pagination = 10;
    public $category_id;
    public $codigo;
    public $nombre;
    public $descripcion;
    public $precio;
    public $precio_oferta;
    public $en_oferta = 0;
    public $unidad_venta = 'kilogramo';
    public $stock = 0;
    public $stock_minimo = 0;
    public $imagen;
    public $peso_promedio;
    public $activo = 1;
    public $destacado = 0;
    public $refrigerado = 1;
    public $fecha_vencimiento;
    public $etiquetas;
    public $selected_id = 0;
    public $search;
    public $filterCategory = '';
    // Campos para venta por monto
    public $monto_venta = null; // Monto en pesos que el cliente pide
    public $cantidad_venta = null; // Cantidad en gramos/kilos calculada

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Productos';
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function render()
    {
        $categories = Category::active()->ordered()->get();

        $data = Product::with('category')
            ->when($this->search, function($query) {
                return $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterCategory, function($query) {
                return $query->where('category_id', $this->filterCategory);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->pagination);

        return view('livewire.productos.productos-controller', [
            'data' => $data,
            'categories' => $categories
        ])
            ->extends('layouts.theme.app')
            ->section('content');
    }

    public function resetUI()
    {
        $this->category_id = '';
        $this->codigo = '';
        $this->nombre = '';
        $this->descripcion = '';
        $this->precio = '';
        $this->precio_oferta = '';
        $this->en_oferta = 0;
        $this->unidad_venta = 'kilogramo';
        $this->stock = 0;
        $this->stock_minimo = 0;
        $this->imagen = null;
        $this->peso_promedio = '';
        $this->activo = 1;
        $this->destacado = 0;
        $this->refrigerado = 1;
        $this->fecha_vencimiento = '';
        $this->etiquetas = '';
        $this->selected_id = 0;
        $this->monto_venta = null;
        $this->cantidad_venta = null;
        $this->resetValidation();
        $this->resetPage();

    }
 /**
         * Convierte un monto en pesos a gramos/kilos según el precio del producto.
         * Si el producto se vende por kilo, regresa los gramos equivalentes.
         * Si se vende por gramo, regresa los gramos equivalentes.
         * Si se vende por pieza, paquete, caja, litro, regresa null.
         */
        public function calcularCantidadPorMonto($monto, $precio, $unidad_venta)
        {
            if ($monto <= 0 || $precio <= 0) return null;
            if (in_array($unidad_venta, ['kilogramo', 'gramo'])) {
                // Si el producto está en oferta, usar el precio de oferta
                $cantidad_kg = $monto / $precio; // cantidad en kilos
                if ($unidad_venta == 'kilogramo') {
                    return round($cantidad_kg * 1000, 2); // gramos
                } else if ($unidad_venta == 'gramo') {
                    return round($cantidad_kg * 1000, 2); // gramos
                }
            }
            // Para otras unidades no aplica
            return null;
        }

        /**
         * Evento Livewire para cuando el usuario ingresa el monto de venta
         * Calcula la cantidad equivalente y la asigna a $cantidad_venta
         */
        public function updatedMontoVenta($value)
        {
            $precio_final = $this->en_oferta && $this->precio_oferta ? $this->precio_oferta : $this->precio;
            $this->cantidad_venta = $this->calcularCantidadPorMonto($value, $precio_final, $this->unidad_venta);
        }

        /**
         * Evento Livewire para cuando el usuario cambia la cantidad manualmente
         * Si cambia la cantidad, borra el monto para evitar confusión
         */
        public function updatedCantidadVenta($value)
        {
            if (!is_null($value)) {
                $this->monto_venta = null;
            }
        }
    public function edit(Product $product)
    {
        $this->selected_id = $product->id;
        $this->category_id = $product->category_id;
        $this->codigo = $product->codigo;
        $this->nombre = $product->nombre;
        $this->descripcion = $product->descripcion;
        $this->precio = $product->precio;
        $this->precio_oferta = $product->precio_oferta;
        $this->en_oferta = $product->en_oferta;
        $this->unidad_venta = $product->unidad_venta;
        $this->stock = $product->stock;
        $this->stock_minimo = $product->stock_minimo;
        $this->peso_promedio = $product->peso_promedio;
        $this->activo = $product->activo;
        $this->destacado = $product->destacado;
        $this->refrigerado = $product->refrigerado;
        $this->fecha_vencimiento = $product->fecha_vencimiento ? $product->fecha_vencimiento->format('Y-m-d') : '';
        $this->etiquetas = $product->etiquetas;
        $this->emit('show-modal', 'open!');
    }

    public function Store()
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'nombre' => 'required|min:3',
            'codigo' => 'nullable|unique:products',
            'precio' => 'required|numeric|min:0',
            'precio_oferta' => 'nullable|numeric|min:0',
            'unidad_venta' => 'required|in:kilogramo,gramo,pieza,paquete,caja,litro',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
        ];

        $messages = [
            'category_id.required' => 'Selecciona una categoría',
            'category_id.exists' => 'La categoría no existe',
            'nombre.required' => 'Ingresa el nombre del producto',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'codigo.unique' => 'Este código ya existe',
            'precio.required' => 'Ingresa el precio',
            'precio.numeric' => 'El precio debe ser numérico',
            'unidad_venta.required' => 'Selecciona la unidad de venta',
            'stock.required' => 'Ingresa el stock',
            'stock_minimo.required' => 'Ingresa el stock mínimo',
        ];

        $this->validate($rules, $messages);

        $imageName = null;
        if ($this->imagen) {
            // Crear directorio si no existe
            $uploadPath = public_path('productos');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $fileName = uniqid() . '.' . $this->imagen->extension();
            $tempFile = $this->imagen->getRealPath();

            // Mover el archivo usando copy y unlink para mejor compatibilidad
            if (copy($tempFile, $uploadPath . DIRECTORY_SEPARATOR . $fileName)) {
                $imageName = '/productos/' . $fileName; // Guardar la ruta completa en la BD
            } else {
                throw new \Exception('No se pudo guardar la imagen');
            }
        }        Product::create([
            'category_id' => $this->category_id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'precio_oferta' => $this->precio_oferta,
            'en_oferta' => $this->en_oferta,
            'unidad_venta' => $this->unidad_venta,
            'stock' => $this->stock,
            'stock_minimo' => $this->stock_minimo,
            'imagen' => $imageName,
            'peso_promedio' => $this->peso_promedio,
            'activo' => $this->activo,
            'destacado' => $this->destacado,
            'refrigerado' => $this->refrigerado,
            'fecha_vencimiento' => $this->fecha_vencimiento,
            'etiquetas' => $this->etiquetas,
        ]);

        $this->resetUI();
        $this->emit('product-added', 'Producto Registrado');
    }

    public function Update()
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'nombre' => 'required|min:3',
            'codigo' => 'nullable|unique:products,codigo,' . $this->selected_id,
            'precio' => 'required|numeric|min:0',
            'precio_oferta' => 'nullable|numeric|min:0',
            'unidad_venta' => 'required|in:kilogramo,gramo,pieza,paquete,caja,litro',
            'stock' => 'required|numeric|min:0',
            'stock_minimo' => 'required|numeric|min:0',
        ];

        $messages = [
            'category_id.required' => 'Selecciona una categoría',
            'nombre.required' => 'Ingresa el nombre del producto',
            'precio.required' => 'Ingresa el precio',
        ];

        $this->validate($rules, $messages);

        try {
            $product = Product::find($this->selected_id);

            $imageName = $product->imagen;
            if ($this->imagen) {
                // Crear directorio si no existe
                $uploadPath = public_path('productos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $fileName = uniqid() . '.' . $this->imagen->extension();
                $tempFile = $this->imagen->getRealPath();

                // Mover el archivo usando copy
                if (copy($tempFile, $uploadPath . DIRECTORY_SEPARATOR . $fileName)) {
                    $imageName = '/productos/' . $fileName; // Guardar la ruta completa en la BD

                    // Eliminar imagen anterior si existe
                    if ($product->imagen) {
                        $oldImagePath = public_path($product->imagen);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                } else {
                    throw new \Exception('No se pudo actualizar la imagen');
                }
            }            $product->update([
                'category_id' => $this->category_id,
                'codigo' => $this->codigo,
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'precio' => $this->precio,
                'precio_oferta' => $this->precio_oferta,
                'en_oferta' => $this->en_oferta,
                'unidad_venta' => $this->unidad_venta,
                'stock' => $this->stock,
                'stock_minimo' => $this->stock_minimo,
                'imagen' => $imageName,
                'peso_promedio' => $this->peso_promedio,
                'activo' => $this->activo,
                'destacado' => $this->destacado,
                'refrigerado' => $this->refrigerado,
                'fecha_vencimiento' => $this->fecha_vencimiento,
                'etiquetas' => $this->etiquetas,
            ]);

            $this->resetUI();
            $this->emit('product-updated', 'Producto Actualizado');
        } catch (\Exception $e) {
            $this->emit('product-error', 'Error: ' . $e->getMessage());
        }
    }

    protected $listeners = [
        'deleteRow' => 'destroy',
        'resetUI' => 'resetUI'
    ];

    public function destroy(Product $product)
    {
        try {
            if ($product->imagen) {
                $imagePath = public_path($product->imagen);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $product->delete();
            $this->resetUI();
            $this->emit('product-deleted', 'Producto eliminado con éxito');
        } catch (\Exception $e) {
            $this->emit('product-error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}
