@include('common.modalHead')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Nombre</label>
            <input type="text" wire:model.lazy="nombre" class="form-control" placeholder="ej: Press Banca">
            @error('nombre') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" wire:model.lazy="descripcion" class="form-control"
                placeholder="ej: Ejercicio para el pecho">
            @error('descripcion') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Miniatura</label>
            <input type="text" wire:model.lazy="miniatura" class="form-control">
            @error('miniatura') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>URL del Video</label>
            <input type="text" wire:model.lazy="video_url" class="form-control"
                placeholder="ej: https://www.youtube.com/watch?v=example">
            @error('video_url') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Grupo Muscular</label>
            <select wire:model.lazy="gm_id" class="form-control">
                <option value="" selected>Elegir</option>
                @foreach($gruposMusculares as $grupo)
                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>
            @error('gm_id') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Etiquetas</label>
            <select wire:model.lazy="tags" class="form-control">
                <option value="" selected>Elegir</option>
                @foreach($etiqueta as $grupo)
                <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="col-sm-12 col-md-6">
        <div class="form-group">
            <label>Equipos</label>
            <select wire:model.lazy="equipos" class="form-control">
                <option value="" selected>Elegir</option>
                @foreach($eq as $e)
                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="col-sm-12 col-md-12">
        <div class="form-group">
            <label>Informacion de Lesion</label>
            <textarea wire:model.lazy="lesion" class="form-control"
                placeholder="Informacion de lesion"></textarea>
            @error('lesion') <span class="text-danger er">{{ $message }}</span>@enderror
        </div>
    </div>
</div>

@include('common.modalFooter')
