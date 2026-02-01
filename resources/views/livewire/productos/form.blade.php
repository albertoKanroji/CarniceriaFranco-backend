@include('common.modalHead')

<div class="row">
    <!-- Información Básica -->
    <div class="col-sm-12">
        <h5 class="mb-3">Información Básica</h5>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Categoría <span class="text-danger">*</span></label>
            <select wire:model.lazy="category_id" class="form-control">
                <option value="">Seleccionar</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nombre <span class="text-danger">*</span></label>
            <input type="text" wire:model.lazy="nombre" class="form-control" placeholder="ej: Bistec de res">
            @error('nombre')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Código</label>
            <input type="text" wire:model.lazy="codigo" class="form-control" placeholder="ej: PROD-001">
            @error('codigo')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group">
            <label>Descripción</label>
            <textarea wire:model.lazy="descripcion" class="form-control" rows="2"
                placeholder="Descripción del producto"></textarea>
            @error('descripcion')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Precios -->
    <div class="col-sm-12">
        <h5 class="mb-3 mt-3">Precios y Oferta</h5>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Precio <span class="text-danger">*</span></label>
            <input type="number" wire:model.lazy="precio" class="form-control" placeholder="0.00" step="0.01" min="0">
            @error('precio')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Precio Oferta</label>
            <input type="number" wire:model.lazy="precio_oferta" class="form-control" placeholder="0.00" step="0.01" min="0">
            @error('precio_oferta')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>¿En Oferta?</label>
            <select wire:model.lazy="en_oferta" class="form-control">
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
            @error('en_oferta')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Unidad de Venta <span class="text-danger">*</span></label>
            <select wire:model.lazy="unidad_venta" class="form-control">
                <option value="kilogramo">Kilogramo</option>
                <option value="gramo">Gramo</option>
                <option value="pieza">Pieza</option>
                <option value="paquete">Paquete</option>
                <option value="caja">Caja</option>
                <option value="litro">Litro</option>
            </select>
            @error('unidad_venta')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Venta por monto (pesos) -->
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Venta por monto (pesos)</label>
            <input type="number" wire:model.lazy="monto_venta" class="form-control" placeholder="Ej: 50" min="0" step="0.01">
            <small class="form-text text-muted">Ingresa el monto en pesos que pide el cliente. Se calculará el peso equivalente automáticamente.</small>
            @if($monto_venta && $cantidad_venta)
                <div class="alert alert-info mt-2 p-2">
                    Equivale a <b>{{ number_format($cantidad_venta, 2) }}</b> gramos
                </div>
            @endif
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Cantidad (gramos/kilos)</label>
            <input type="number" wire:model.lazy="cantidad_venta" class="form-control" placeholder="Ej: 250" min="0" step="0.01">
            <small class="form-text text-muted">Si el cliente pide por peso, ingrésalo aquí. Si llenas el monto, este campo se calcula automáticamente.</small>
        </div>
    </div>

    <!-- Inventario -->
    <div class="col-sm-12">
        <h5 class="mb-3 mt-3">Inventario</h5>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Stock <span class="text-danger">*</span></label>
            <input type="number" wire:model.lazy="stock" class="form-control" placeholder="0" step="0.01" min="0">
            @error('stock')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Stock Mínimo <span class="text-danger">*</span></label>
            <input type="number" wire:model.lazy="stock_minimo" class="form-control" placeholder="0" step="0.01" min="0">
            @error('stock_minimo')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Peso Promedio (kg)</label>
            <input type="number" wire:model.lazy="peso_promedio" class="form-control" placeholder="0.00" step="0.01" min="0">
            <small class="form-text text-muted">Para productos vendidos por pieza</small>
            @error('peso_promedio')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Configuración -->
    <div class="col-sm-12">
        <h5 class="mb-3 mt-3">Configuración</h5>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Estado</label>
            <select wire:model.lazy="activo" class="form-control">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
            </select>
            @error('activo')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Destacado</label>
            <select wire:model.lazy="destacado" class="form-control">
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
            @error('destacado')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Refrigerado</label>
            <select wire:model.lazy="refrigerado" class="form-control">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
            @error('refrigerado')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-3">
        <div class="form-group">
            <label>Fecha Vencimiento</label><span class="text-danger">*</span></label>
            <input type="date" wire:model.lazy="fecha_vencimiento" class="form-control">
            @error('fecha_vencimiento')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Etiquetas</label>
            <input type="text" wire:model.lazy="etiquetas" class="form-control"
                placeholder="premium, especial, importado (separadas por comas)">
            @error('etiquetas')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Imagen</label>
            <input type="file" wire:model="imagen" class="form-control" accept="image/*">
            @error('imagen')
                <span class="text-danger er">{{ $message }}</span>
            @enderror

            @if ($imagen)
                <div class="mt-2">
                    <img src="{{ $imagen->temporaryUrl() }}"
                         style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                </div>
            @endif
        </div>
    </div>
</div>

@include('common.modalFooter')
