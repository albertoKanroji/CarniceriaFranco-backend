<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" role="dialog" style="backdrop-filter: blur(10px);">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>{{$componentName}}</b> | Gestionar Despacho
                    @if($selectedSaleId)
                        - Folio: {{ \App\Models\Sale::find($selectedSaleId)?->folio }}
                    @endif
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">

                @if($selectedSaleId && count($saleDetails) > 0)
                    @php
                        $sale = \App\Models\Sale::find($selectedSaleId);
                        $todosDespachados = collect($saleDetails)->every('estado_despacho');
                    @endphp

                    <!-- Estado del Pedido -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Estado actual:</strong>
                                        <span class="badge badge-{{ $sale->estado_envio == 'Pendiente' ? 'secondary' : ($sale->estado_envio == 'Procesando' ? 'warning' : 'success') }}">
                                            {{ $sale->estado_envio == 'Listo_para_enviar' ? 'Listo para enviar' : $sale->estado_envio }}
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            {{ collect($saleDetails)->where('estado_despacho', 1)->count() }}/{{ count($saleDetails) }} productos despachados
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cliente Info -->
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="mb-3">Información del Cliente</h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Cliente:</label>
                                <p><strong>{{ $sale->customer ? $sale->customer->nombre . ' ' . $sale->customer->apellido : 'Cliente General' }}</strong></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Total:</label>
                                <p><strong style="color: #28a745;">${{ number_format($sale->total, 2) }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Productos -->
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="mb-3 mt-3">Productos del Pedido</h5>
                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead style="background: #3B3F5C">
                                        <tr>
                                            <th class="table-th text-center text-white">ESTADO</th>
                                            <th class="table-th text-center text-white">PRODUCTO</th>
                                            <th class="table-th text-center text-white">CANTIDAD</th>
                                            <th class="table-th text-center text-white">PRECIO</th>
                                            <th class="table-th text-center text-white">SUBTOTAL</th>
                                            <th class="table-th text-center text-white">DESPACHAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($saleDetails as $detail)
                                            <tr class="{{ $detail['estado_despacho'] ? 'table-success' : '' }}">
                                                <td class="text-center">
                                                    @if($detail['estado_despacho'])
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Listo
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">
                                                            <i class="fas fa-clock"></i> Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <h6><strong>{{ $detail['producto_nombre'] }}</strong></h6>
                                                    @if($detail['producto_codigo'])
                                                        <small class="text-muted">{{ $detail['producto_codigo'] }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-info">
                                                        {{ number_format($detail['cantidad'], 2) }} {{ $detail['unidad_venta'] }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <h6>${{ number_format($detail['precio_unitario'], 2) }}</h6>
                                                    @if($detail['precio_oferta'])
                                                        <small class="text-success">Oferta: ${{ number_format($detail['precio_oferta'], 2) }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <h6><strong>${{ number_format($detail['total'], 2) }}</strong></h6>
                                                </td>
                                                <td class="text-center">
                                                    <div class="custom-control custom-switch custom-control-lg">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               id="switch{{ $detail['id'] }}"
                                                               {{ $detail['estado_despacho'] ? 'checked' : '' }}
                                                               wire:click="toggleProductDespacho({{ $detail['id'] }})">
                                                        <label class="custom-control-label" for="switch{{ $detail['id'] }}">
                                                            <span class="{{ $detail['estado_despacho'] ? 'text-success' : 'text-muted' }}">
                                                                {{ $detail['estado_despacho'] ? 'Despachado' : 'Pendiente' }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de Enviar Pedido -->
                    @if($sale && $sale->estado_envio == 'Listo_para_enviar')
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-success text-center mt-3">
                                    <h6><i class="fas fa-check-circle"></i> ¡Todos los productos han sido despachados!</h6>
                                    <button class="btn btn-success btn-lg" wire:click="enviarPedido">
                                        <i class="fas fa-shipping-fast"></i> ENVIAR PEDIDO
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted">
                        <h5>No hay productos para mostrar</h5>
                    </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="closeModal()" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>
</div>
