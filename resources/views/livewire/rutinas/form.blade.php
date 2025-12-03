@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.lazy="nombre" class="form-control" placeholder="ej: Press Banca">
            @error('nombre')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Imagen (url)</label>
            <input type="text" wire:model.lazy="imagen" class="form-control">
            @error('imagen')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Descripción</label>
            <textarea wire:model.lazy="descripcion" class="form-control" placeholder="ej: Ejercicio para el pecho"></textarea>
            @error('descripcion')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Tipo</label>
            <select wire:model.lazy="tipo" class="form-control">
                <option value="">Seleccionar Estado</option>
                <option value="Principiante">Principiante</option>
                <option value="Intermedio">Intermedio</option>
                <option value="Avanzado">Avanzado</option>
            </select>
            @error('tipo')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>



    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Sexo</label>
            <select wire:model.lazy="sexo" class="form-control">
                <option value="">Seleccionar Estado</option>
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
            </select>
            @error('sexo')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
 <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Puntaje</label>
            <input type="text" wire:model.lazy="puntaje" class="form-control">
            @error('puntaje')
                <span class="text-danger er">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@include('common.modalFooter')
