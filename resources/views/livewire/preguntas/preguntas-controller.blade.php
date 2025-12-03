<div>
<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        Agregar Nueva Pregunta
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="nuevaPregunta" class="form-label">Pregunta</label>
            <input type="text" class="form-control" id="nuevaPregunta" wire:model="nuevaPregunta" placeholder="Escribe la nueva pregunta...">
        </div>

        <h6>Opciones</h6>
        @foreach ($nuevasOpciones as $index => $opcion)
            <div class="row mb-2">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Opción"
                           wire:model="nuevasOpciones.{{ $index }}.opcion">
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" placeholder="Valor"
                           wire:model="nuevasOpciones.{{ $index }}.valor">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-danger btn-sm" wire:click.prevent="eliminarOpcion({{ $index }})">Eliminar</button>
                </div>
            </div>
        @endforeach

        <button class="btn btn-secondary btn-sm mb-3" wire:click.prevent="agregarOpcion">
            + Agregar Opción
        </button>

        <div>
            <button class="btn btn-success" wire:click.prevent="guardarPregunta">
                Guardar Pregunta
            </button>
        </div>
    </div>
</div>


<div class="container py-4">
    <h3 class="mb-4">Lista de Preguntas con Opciones</h3>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="accordion" id="preguntasAccordion">
        @foreach($preguntasConOpciones as $index => $pregunta)
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ $index }}">
                <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                    {{ $pregunta->pregunta }}
                </button>
            </h2>
            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#preguntasAccordion">
                <div class="accordion-body">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Opción</th>
                                <th>Valor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pregunta->respuestasOpciones as $i => $opcion)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <input type="text" class="form-control"
                                           wire:model.defer="editingValues.{{ $opcion->id }}.opcion"
                                           value="{{ $opcion->opcion }}">
                                </td>
                                <td>
                                    <input type="number" class="form-control"
                                           wire:model.defer="editingValues.{{ $opcion->id }}.valor"
                                           value="{{ $opcion->valor }}">
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm"
                                            wire:click="updateOpcion({{ $opcion->id }})">
                                        Actualizar
                                    </button>
                                    <button class="btn btn-danger btn-sm"
                                            wire:click="deleteOpcion({{ $opcion->id }})"
                                            onclick="return confirm('¿Seguro que deseas eliminar esta opción?')">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
