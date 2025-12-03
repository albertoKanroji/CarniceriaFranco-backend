<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'codigo',
        'nombre',
        'descripcion',
        'precio',
        'precio_oferta',
        'en_oferta',
        'unidad_venta',
        'stock',
        'stock_minimo',
        'imagen',
        'imagenes',
        'peso_promedio',
        'activo',
        'destacado',
        'refrigerado',
        'fecha_vencimiento',
        'etiquetas',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'precio_oferta' => 'decimal:2',
        'stock' => 'decimal:2',
        'stock_minimo' => 'decimal:2',
        'peso_promedio' => 'decimal:2',
        'en_oferta' => 'boolean',
        'activo' => 'boolean',
        'destacado' => 'boolean',
        'refrigerado' => 'boolean',
        'fecha_vencimiento' => 'date',
        'imagenes' => 'array', // Para manejar JSON
    ];

    protected $attributes = [
        'en_oferta' => false,
        'activo' => true,
        'destacado' => false,
        'refrigerado' => true,
        'stock' => 0,
        'stock_minimo' => 0,
        'unidad_venta' => 'kilogramo',
    ];

    // Relación: Un producto pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Accessor para obtener el precio final (con oferta o normal)
    public function getPrecioFinalAttribute()
    {
        return $this->en_oferta && $this->precio_oferta ? $this->precio_oferta : $this->precio;
    }

    // Accessor para verificar si hay stock disponible
    public function getTieneStockAttribute()
    {
        return $this->stock > 0;
    }

    // Accessor para verificar si está bajo en stock
    public function getStockBajoAttribute()
    {
        return $this->stock <= $this->stock_minimo;
    }

    // Scope para productos activos
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    // Scope para productos en oferta
    public function scopeEnOferta($query)
    {
        return $query->where('en_oferta', true);
    }

    // Scope para productos destacados
    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    // Scope para productos con stock disponible
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Scope para productos con stock bajo
    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock <= stock_minimo');
    }

    // Scope para buscar por nombre o código
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('nombre', 'like', "%{$term}%")
              ->orWhere('codigo', 'like', "%{$term}%")
              ->orWhere('descripcion', 'like', "%{$term}%");
        });
    }

    // Scope para filtrar por categoría
    public function scopePorCategoria($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
