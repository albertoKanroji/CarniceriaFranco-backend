@include('common.modalHead')

<div class="row">
    <!-- Información Básica -->
    <div class="col-sm-12">
        <h5 class="mb-3">Información Personal</h5>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Nombre <span class="text-danger">*</span></label>
            <input type="text" wire:model.lazy="nombre" class="form-control" placeholder="ej: Luis">
            @error('nombre')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Apellido Paterno <span class="text-danger">*</span></label>
            <input type="text" wire:model.lazy="apellido" class="form-control" placeholder="ej: García">
            @error('apellido')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Apellido Materno</label>
            <input type="text" wire:model.lazy="apellido2" class="form-control" placeholder="ej: López">
            @error('apellido2')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Contacto -->
    <div class="col-sm-12">
        <h5 class="mb-3 mt-3">Información de Contacto</h5>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" wire:model.lazy="correo" class="form-control" placeholder="ej: cliente@correo.com">
            @error('correo')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" wire:model.lazy="telefono" class="form-control" placeholder="ej: 5512345678">
            @error('telefono')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Contraseña <span class="text-danger">*</span></label>
            <input type="password" wire:model.lazy="password" class="form-control" placeholder="Mínimo 6 caracteres">
            @error('password')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Dirección -->
    <div class="col-sm-12">
        <h5 class="mb-3 mt-3">Dirección</h5>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label>Dirección Completa</label>
            <input type="text" wire:model.lazy="direccion" class="form-control" placeholder="Calle, número, colonia">
            @error('direccion')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Ciudad</label>
            <input type="text" wire:model.lazy="ciudad" class="form-control" placeholder="ej: Ciudad de México">
            @error('ciudad')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Estado</label>
            <input type="text" wire:model.lazy="estado" class="form-control" placeholder="ej: CDMX">
            @error('estado')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Código Postal</label>
            <input type="text" wire:model.lazy="codigo_postal" class="form-control" placeholder="ej: 12345">
            @error('codigo_postal')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- Información Fiscal y Comercial -->
    <div class="col-sm-12">
        <h5 class="mb-3 mt-3">Información Comercial</h5>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>RFC</label>
            <input type="text" wire:model.lazy="rfc" class="form-control" placeholder="ej: GALU800101XXX">
            @error('rfc')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Tipo de Cliente <span class="text-danger">*</span></label>
            <select wire:model.lazy="tipo_cliente" class="form-control">
                <option value="minorista">Minorista</option>
                <option value="mayorista">Mayorista</option>
                <option value="distribuidor">Distribuidor</option>
            </select>
            @error('tipo_cliente')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-4">
        <div class="form-group">
            <label>Estatus <span class="text-danger">*</span></label>
            <select wire:model.lazy="estatus" class="form-control">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
                <option value="suspendido">Suspendido</option>
            </select>
            @error('estatus')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Límite de Crédito</label>
            <input type="number" wire:model.lazy="limite_credito" class="form-control" placeholder="0.00" step="0.01">
            @error('limite_credito')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Descuento Preferencial (%)</label>
            <input type="number" wire:model.lazy="descuento_preferencial" class="form-control" placeholder="0.00" step="0.01" max="100">
            @error('descuento_preferencial')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label>Notas</label>
            <textarea wire:model.lazy="notas" class="form-control" rows="3" placeholder="Observaciones sobre el cliente"></textarea>
            @error('notas')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')
