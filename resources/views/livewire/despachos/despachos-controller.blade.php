<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
            </div>

            <div class="row justify-content-between align-items-start">
                <div class="col-lg-9 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label class="text-muted" style="font-size: 12px;">Folio</label>
                            <input type="text" wire:model="filtroFolio" placeholder="Buscar folio..." class="form-control">
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                            <label class="text-muted" style="font-size: 12px;">Cliente</label>
                            <input type="text" wire:model="filtroCliente" placeholder="Buscar cliente..." class="form-control">
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label class="text-muted" style="font-size: 12px;">Estado</label>
                            <select wire:model="filtroEstado" class="form-control">
                                <option value="">Todos</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Procesando">Procesando</option>
                                <option value="Listo_para_enviar">Listo para enviar</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 mb-3 d-flex align-items-end">
                            <button wire:click="clearFilters" class="btn btn-dark btn-block">
                                <i class="fas fa-redo"></i> Limpiar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="d-flex justify-content-end align-items-center flex-wrap">
                        <button class="btn btn-success mr-2 mb-2"
                                wire:click="openCreateOrderModal"
                                wire:loading.attr="disabled"
                                wire:target="openCreateOrderModal">
                            <span wire:loading.remove wire:target="openCreateOrderModal">
                                <i class="fas fa-cart-plus"></i> Crear pedido
                            </span>
                            <span wire:loading wire:target="openCreateOrderModal">
                                <i class="fas fa-spinner fa-spin"></i> Abriendo...
                            </span>
                        </button>
                        <span class="badge badge-warning" style="font-size: 12px; margin-right: 10px;">
                            <i class="fas fa-exclamation-triangle"></i> Urgente: +3 horas
                        </span>
                    </div>
                </div>
            </div>

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mt-1">
                        <thead style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-center">FOLIO</th>
                                <th class="table-th text-center">CLIENTE</th>
                                <th class="table-th text-center">FECHA VENTA</th>
                                <th class="table-th text-center">TOTAL</th>
                                <th class="table-th text-center">ESTADO</th>
                                <th class="table-th text-center">ITEMS</th>
                                <th class="table-th text-center">TIEMPO</th>
                                <th class="table-th text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventas as $venta)
                                @php
                                    $horasTranscurridas = \Carbon\Carbon::parse($venta->fecha_venta)->diffInHours(\Carbon\Carbon::now());
                                    $isUrgent = in_array($venta->id, $ventasUrgentes);
                                    $badgeClass = '';
                                    $badgeText = '';

                                    switch($venta->estado_envio) {
                                        case 'Pendiente':
                                            $badgeClass = 'badge-secondary';
                                            $badgeText = 'Pendiente';
                                            break;
                                        case 'Procesando':
                                            $badgeClass = 'badge-warning';
                                            $badgeText = 'Procesando';
                                            break;
                                        case 'Listo_para_enviar':
                                            $badgeClass = 'badge-success';
                                            $badgeText = 'Listo para enviar';
                                            break;
                                    }
                                @endphp
                                <tr class="{{ $isUrgent ? 'table-danger' : '' }}"
                                    style="{{ $isUrgent ? 'animation: blink 2s infinite;' : '' }}">
                                    <td class="text-center">
                                        <h6><strong>{{ $venta->folio }}</strong></h6>
                                        @if($isUrgent)
                                            <span class="badge badge-danger badge-sm">
                                                <i class="fas fa-exclamation-triangle"></i> URGENTE
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $venta->customer ? $venta->customer->nombre . ' ' . $venta->customer->apellido : 'Cliente General' }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6>{{ $venta->fecha_venta->format('d/m/Y H:i') }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <h6><strong style="color: #28a745;">${{ number_format($venta->total, 2) }}</strong></h6>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-info">{{ $venta->details->count() }} items</span>
                                    </td>
                                    <td class="text-center">
                                        <h6 class="{{ $isUrgent ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                            {{ $horasTranscurridas }}h {{ \Carbon\Carbon::parse($venta->fecha_venta)->diffInMinutes(\Carbon\Carbon::now()) % 60 }}m
                                        </h6>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click="openModal({{ $venta->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="openModal({{ $venta->id }})"
                                            class="btn btn-primary btn-rounded mb-2" title="Gestionar Despacho">
                                            <span wire:loading.remove wire:target="openModal({{ $venta->id }})">
                                                <i class="fas fa-boxes"></i>
                                            </span>
                                            <span wire:loading wire:target="openModal({{ $venta->id }})">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <h5>No hay pedidos pendientes de despacho</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $ventas->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('livewire.despachos.modal')
    @include('livewire.despachos.create-order-modal')
</div>

<style>
    @keyframes blink {
        0%, 50% { background-color: #f8d7da; }
        51%, 100% { background-color: #ffffff; }
    }

    .table-success {
        background-color: #d4edda !important;
    }

    .custom-switch .custom-control-label::before {
        width: 2.25rem;
        height: 1.25rem;
        background-color: #adb5bd;
        border: none;
    }

    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745;
    }

    .custom-switch .custom-control-label::after {
        width: 1rem;
        height: 1rem;
        top: 0.125rem;
        left: -2.25rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('show-modal', function() {
            $('#theModal').modal('show');
        });

        window.livewire.on('despacho-updated', Msg => {
            noty(Msg, 1);
        });

        window.livewire.on('despacho-error', Msg => {
            noty(Msg, 2);
        });

        window.livewire.on('pedido-enviado', Msg => {
            $('#theModal').modal('hide');
            noty(Msg);
            setTimeout(() => {
                location.reload();
            }, 1500);
        });

        window.livewire.on('hide-modal', function() {
            $('#theModal').modal('hide');
        });

        window.livewire.on('show-create-order-modal', function() {
            $('#createOrderModal').modal('show');
        });

        window.livewire.on('hide-create-order-modal', function() {
            $('#createOrderModal').modal('hide');
        });

        window.livewire.on('pedido-creado', Msg => {
            $('#createOrderModal').modal('hide');
            noty(Msg, 1);
        });
    });

    function noty(msg, type = 1) {
        var n = new Noty({
            type: type == 1 ? 'success' : type == 2 ? 'error' : 'info',
            layout: 'topRight',
            text: msg,
            timeout: 3000
        }).show();
    }
</script>
