<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden;">
            <div class="widget-heading" style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%); padding: 20px;">
                <h4 class="card-title" style="color: #C9A961; font-weight: 800; font-size: 24px; margin-bottom: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    {{ $componentName }} | <span style="background: rgba(201, 169, 97, 0.2); padding: 5px 12px; border-radius: 15px; border: 2px solid #C9A961;">{{ $ventas->total() }}</span>
                </h4>
                <ul class="tabs tab-pills">
                    <li>
                        <a href="javascript:void(0)" class="tabmenu" data-toggle="modal"
                            data-target="#theModal" style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; padding: 10px 24px; border-radius: 8px; font-weight: 700; box-shadow: 0 4px 15px rgba(201, 169, 97, 0.5); transition: all 0.3s; border: 2px solid #D4B570;">Agregar</a>
                    </li>
                </ul>
            </div>

            <!-- Estadísticas -->
            <div class="row mb-4 mt-3" style="padding: 0 20px;">
                <div class="col-md-4">
                    <div class="card text-center" style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; border: 3px solid #D4B570; border-radius: 12px; box-shadow: 0 6px 20px rgba(201, 169, 97, 0.4); transform: translateY(0); transition: all 0.3s;">
                        <div class="card-body" style="padding: 25px;">
                            <div style="background: rgba(44, 44, 44, 0.15); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                <i class="fas fa-dollar-sign" style="font-size: 28px; color: #2C2C2C;"></i>
                            </div>
                            <h3 style="font-weight: 800; font-size: 32px; margin-bottom: 8px; text-shadow: 1px 1px 2px rgba(255,255,255,0.5);">${{ number_format($totales['total_ventas'], 2) }}</h3>
                            <p class="mb-0" style="font-size: 15px; font-weight: 700; opacity: 0.9;">Total Periodo</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center" style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%); color: #C9A961; border: 3px solid #8B7346; border-radius: 12px; box-shadow: 0 6px 20px rgba(44, 44, 44, 0.4); transform: translateY(0); transition: all 0.3s;">
                        <div class="card-body" style="padding: 25px;">
                            <div style="background: rgba(201, 169, 97, 0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                <i class="fas fa-shopping-cart" style="font-size: 28px; color: #C9A961;"></i>
                            </div>
                            <h3 style="font-weight: 800; font-size: 32px; margin-bottom: 8px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">{{ $totales['numero_ventas'] }}</h3>
                            <p class="mb-0" style="font-size: 15px; font-weight: 700; opacity: 0.95;">Número de Ventas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center" style="background: linear-gradient(135deg, #B8935A 0%, #8B7346 100%); color: #2C2C2C; border: 3px solid #C9A961; border-radius: 12px; box-shadow: 0 6px 20px rgba(184, 147, 90, 0.4); transform: translateY(0); transition: all 0.3s;">
                        <div class="card-body" style="padding: 25px;">
                            <div style="background: rgba(44, 44, 44, 0.15); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                <i class="fas fa-calendar-day" style="font-size: 28px; color: #2C2C2C;"></i>
                            </div>
                            <h3 style="font-weight: 800; font-size: 32px; margin-bottom: 8px; text-shadow: 1px 1px 2px rgba(255,255,255,0.5);">${{ number_format($totales['ventas_hoy'], 2) }}</h3>
                            <p class="mb-0" style="font-size: 15px; font-weight: 700; opacity: 0.9;">Ventas Hoy</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="widget-content" style="padding: 20px;">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <input type="text" wire:model="search" class="form-control" placeholder="Buscar por folio o cliente..."
                               style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 15px; font-size: 14px; font-weight: 500; color: #495057; transition: all 0.3s;">
                    </div>
                    <div class="col-md-2 mb-3">
                        <select wire:model="filtroEstatus" class="form-control"
                                style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 15px; font-size: 14px; font-weight: 500; color: #495057; transition: all 0.3s;">
                            <option value="">Todos los estatus</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="completada">Completada</option>
                            <option value="cancelada">Cancelada</option>
                            <option value="entregada">Entregada</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <select wire:model="filtroMetodoPago" class="form-control"
                                style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 15px; font-size: 14px; font-weight: 500; color: #495057; transition: all 0.3s;">
                            <option value="">Todos los métodos</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="credito">Crédito</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <input type="date" wire:model="fechaInicio" class="form-control"
                               style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 15px; font-size: 14px; font-weight: 500; color: #495057; transition: all 0.3s;">
                    </div>
                    <div class="col-md-2 mb-3">
                        <input type="date" wire:model="fechaFin" class="form-control"
                               style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 15px; font-size: 14px; font-weight: 500; color: #495057; transition: all 0.3s;">
                    </div>
                    <div class="col-md-1 mb-3">
                        <button wire:click="clearFilters" class="btn btn-block"
                                style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; border: none; border-radius: 8px; padding: 10px; font-weight: 600; box-shadow: 0 3px 10px rgba(108, 117, 125, 0.3); transition: all 0.3s;">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="table table-bordered mt-1" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden;">
                        <thead style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%);">
                            <tr>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">FOLIO</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">FECHA</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">CLIENTE</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">ITEMS</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">SUBTOTAL</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">DESCUENTO</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">TOTAL</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">MÉTODO PAGO</th>
                                <th class="table-th" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">ESTATUS</th>
                                <th class="table-th text-center" style="color: #C9A961; font-weight: 800; padding: 15px; border: none; font-size: 13px; letter-spacing: 0.5px;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ventas as $index => $venta)
                                <tr style="{{ $index % 2 == 0 ? 'background-color: #f8f9fa;' : 'background-color: white;' }} transition: all 0.2s;">
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <h6 class="mb-0" style="color: #212529; font-weight: 700; font-size: 14px;">{{ $venta->folio }}</h6>
                                    </td>
                                    <td style="color: #495057; font-weight: 500; padding: 15px; vertical-align: middle; font-size: 14px;">{{ $venta->fecha_venta->format('d/m/Y H:i') }}</td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <div style="color: #212529; font-weight: 600; font-size: 14px; margin-bottom: 3px;">{{ $venta->customer->nombre }} {{ $venta->customer->apellido }}</div>
                                        <small style="color: #6c757d; font-size: 12px;">{{ $venta->customer->email }}</small>
                                    </td>
                                    <td class="text-center" style="padding: 15px; vertical-align: middle;">
                                        <span class="badge" style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; padding: 6px 12px; font-size: 13px; font-weight: 700; border-radius: 15px; border: 2px solid #D4B570;">{{ $venta->details->count() }} items</span>
                                    </td>
                                    <td style="color: #495057; font-weight: 600; padding: 15px; vertical-align: middle; font-size: 14px;">${{ number_format($venta->subtotal, 2) }}</td>
                                    <td style="color: #dc3545; font-weight: 600; padding: 15px; vertical-align: middle; font-size: 14px;">${{ number_format($venta->descuento, 2) }}</td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        <h6 class="mb-0" style="color: #28a745; font-weight: 700; font-size: 16px;">${{ number_format($venta->total, 2) }}</h6>
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        @if($venta->metodo_pago == 'efectivo')
                                            <span class="badge" style="background: #28a745; color: white; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Efectivo</span>
                                        @elseif($venta->metodo_pago == 'tarjeta')
                                            <span class="badge" style="background: #007bff; color: white; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Tarjeta</span>
                                        @elseif($venta->metodo_pago == 'transferencia')
                                            <span class="badge" style="background: #17a2b8; color: white; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Transferencia</span>
                                        @else
                                            <span class="badge" style="background: #ffc107; color: #000; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Crédito</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px; vertical-align: middle;">
                                        @if($venta->estatus == 'completada')
                                            <span class="badge" style="background: #28a745; color: white; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Completada</span>
                                        @elseif($venta->estatus == 'pendiente')
                                            <span class="badge" style="background: #ffc107; color: #000; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Pendiente</span>
                                        @elseif($venta->estatus == 'cancelada')
                                            <span class="badge" style="background: #dc3545; color: white; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Cancelada</span>
                                        @else
                                            <span class="badge" style="background: #17a2b8; color: white; padding: 6px 12px; font-size: 12px; font-weight: 600; border-radius: 15px;">Entregada</span>
                                        @endif
                                    </td>
                                    <td class="text-center" style="padding: 15px; vertical-align: middle;">
                                        <button wire:click="viewDetail({{ $venta->id }})" class="btn mtmobile"
                                            title="Ver Detalle"
                                            style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; border: 2px solid #D4B570; padding: 8px 12px; border-radius: 8px; font-weight: 700; box-shadow: 0 4px 12px rgba(201, 169, 97, 0.4); transition: all 0.3s; margin: 2px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($venta->estatus != 'cancelada')
                                            <button onclick="confirmCancel({{ $venta->id }})"
                                                class="btn mtmobile" title="Cancelar"
                                                style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%); color: #C9A961; border: 2px solid #8B7346; padding: 8px 12px; border-radius: 8px; font-weight: 700; box-shadow: 0 4px 12px rgba(44, 44, 44, 0.4); transition: all 0.3s; margin: 2px;">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $ventas->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle -->
    @if($selectedSaleId)
        @include('livewire.ventas.detail-form')
    @endif
</div>

<script>
    function confirmCancel(saleId) {
        Swal.fire({
            title: '¿Cancelar Venta?',
            text: "Se devolverá el stock de los productos",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('cancelSale', saleId);
            }
        })
    }

    document.addEventListener('DOMContentLoaded', function() {
        window.livewire.on('sale-cancelled', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: msg
            });
        });

        window.livewire.on('sale-error', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            });
        });

        window.livewire.on('show-detail-modal', () => {
            $('#detailModal').modal('show');
        });
    });
</script>
