@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.lazy="nombre" class="form-control" placeholder="ej: Press Banca">
            @error('nombre') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label>Descripción</label>
            <textarea wire:model.lazy="descripcion" class="form-control" placeholder="ej: Ejercicio para el pecho" rows="10" cols="50"></textarea>

            @error('descripcion') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Miniatura URL</label>
            <input type="text" wire:model.lazy="imagen" class="form-control">
            @error('imagen') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>

</div>

@include('common.modalFooter')
