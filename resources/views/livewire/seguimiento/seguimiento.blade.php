<div wire:ignore.self class="modal fade" id="ModalImagenesCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title text-white">🖼️ Seguimiento del Cliente</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
          <div class="modal-body p-5 bg-light">
                @if (empty($imagenesSeguimientoCliente))
                    <p class="text-center text-muted">No hay imágenes de seguimiento disponibles.</p>
                @else
                    <div class="position-relative ml-4">
                        <!-- Línea central -->
                        <div class="position-absolute top-0 bottom-0 start-0 w-1 bg-primary rounded"></div>

                        @foreach ($imagenesSeguimientoCliente as $index => $img)
                            <div class="d-flex mb-5 position-relative">
                                <!-- Punto en la línea -->
                                <div class="position-absolute start-0 translate-middle bg-primary rounded-circle"
                                    style="width: 20px; height: 20px; top: 0.5rem; left: -10px;"></div>

                                <!-- Contenido -->
                                <div class="card ml-4 shadow w-100">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-md-4">
                                            <img src="{{ $img['image'] }}" alt="Seguimiento" class="img-fluid rounded-start">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <p class="mb-1"><strong>Peso:</strong> {{ $img['peso'] }}</p>
                                                <p class="mb-1"><strong>Comentarios:</strong> {{ $img['comentarios'] }}</p>
                                                <p class="mb-0 text-muted"><strong>Fecha:</strong> {{ $img['created_at'] }} | <strong>Mes:</strong> {{ $img['mes'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-dark close-btn text-info" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
