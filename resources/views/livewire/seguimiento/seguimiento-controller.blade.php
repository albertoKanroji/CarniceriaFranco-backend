<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>

            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-center">NOMBRE</th>
                                <th class="table-th text-center">correo</th>

                                <th class="table-th text-center">ESTADO</th>
                                <th class="table-th text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td class="text-center">
                                        <h6>{{ $cliente->nombre }} {{ $cliente->apellido }} {{ $cliente->apellido2 }}
                                        </h6>
                                    </td>

                                    <td class="text-center">
                                        <h6>{{ $cliente->correo }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $cliente->estado }}</h6>
                                    </td>


                                    <td class="text-center">

                                        <a href="javascript:void(0)"
                                            wire:click="verImagenesCliente({{ $cliente->id }})"
                                            class="btn btn-primary btn-rounded mb-2" title="Editar">
                                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                                stroke-width="2" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round" class="css-i6dzq1">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $clientes->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.seguimiento.seguimiento')

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('video-added', Msg => {
            $('#theModal').modal('hide');
            resetInputFile();
            noty(Msg);
        });
        window.livewire.on('video-updated', Msg => {
            $('#theModal').modal('hide');
            resetInputFile();
            noty(Msg);
        });
        window.livewire.on('video-deleted', Msg => {
            noty(Msg);
        });
        window.livewire.on('hide-modal', Msg => {
            $('#theModal').modal('hide');
        });
        window.livewire.on('show-modal', Msg => {
            $('#theModal').modal('show');
        });
        window.livewire.on('modal-videos-hide', Msg => {
            $('#EjerciciosRutinas').modal('hide');
        });
        window.livewire.on('modal-videos', Msg => {
            $('#ModalImagenesCliente').modal('show');
        });
    });

    function resetInputFile() {
        $('input[type=file]').val('');
    }

    function Confirm(id) {
        swal({
            title: 'CONFIRMAR',
            text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
            type: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#fff',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('deleteRow', id);
                swal.close();
            }
        });
    }
</script>
