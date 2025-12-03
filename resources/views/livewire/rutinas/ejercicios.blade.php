<div wire:ignore.self class="modal fade" id="EjerciciosRutinas" tabindex="-1" role="dialog"
    style="backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>Agregar ejercicios a las rutinas</b>
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="search" class="form-label">Buscar Video:</label>
                            <input type="text" wire:model="search" class="form-control"
                                placeholder="Buscar por nombre de video...">
                        </div>
                    </div>
                    <!-- Selección de Día -->

                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="dia" class="form-label">Selecciona el día:</label>
                            <select wire:model="diaSeleccionado" class="form-control">
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="text-white">Nombre</th>
                                <th class="text-white">Grupo Muscular</th>
                                <th class="text-white">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($videos as $video)
                                <tr>
                                    <td>{{ $video->nombre }}</td>
                                    <td>{{ $video->grupoMuscular->nombre }}</td>
                                    <td class="text-center">
                                        @php
                                            $videoAgregado = collect($videosAgregados)->firstWhere('id', $video->id);
                                        @endphp

                                        @if ($videoAgregado)
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <span class="badge bg-success text-white p-2 rounded-pill">
                                                    ✅ Agregado
                                                    @if ($videoAgregado['dia'])
                                                        ({{ $videoAgregado['dia'] }})
                                                    @endif
                                                </span>
                                                <button wire:click="eliminarEjercicio({{ $video->id }})"
                                                    class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                                    ❌ Quitar
                                                </button>
                                            </div>
                                        @else
                                            <button wire:click="agregarEjercicio({{ $video->id }})"
                                                class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                                                ➕ Agregar
                                            </button>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info"
                    data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>
