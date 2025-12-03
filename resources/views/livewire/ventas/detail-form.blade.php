@php
    $sale = \App\Models\Sale::with(['customer', 'details.product'])->find($selectedSaleId);
@endphp

@if($sale)
<div wire:ignore.self id="detailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%); border: none;">
                <h5 class="modal-title" style="color: #C9A961; font-weight: 800; font-size: 22px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                    <i class="fas fa-receipt"></i> Detalle de Venta - {{ $sale->folio }}
                </h5>
                <h6 class="ml-auto mr-3">
                    @if($sale->estatus == 'completada')
                        <span class="badge" style="background: #28a745; color: white; padding: 8px 16px; font-size: 14px; font-weight: 600; border-radius: 20px;">Completada</span>
                    @elseif($sale->estatus == 'pendiente')
                        <span class="badge" style="background: #ffc107; color: #000; padding: 8px 16px; font-size: 14px; font-weight: 600; border-radius: 20px;">Pendiente</span>
                    @elseif($sale->estatus == 'cancelada')
                        <span class="badge" style="background: #dc3545; color: white; padding: 8px 16px; font-size: 14px; font-weight: 600; border-radius: 20px;">Cancelada</span>
                    @else
                        <span class="badge" style="background: #17a2b8; color: white; padding: 8px 16px; font-size: 14px; font-weight: 600; border-radius: 20px;">Entregada</span>
                    @endif
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" wire:click="closeDetail">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Información del Cliente -->
                    <div class="col-md-6">
                        <div class="card mb-3" style="border: 3px solid #D4B570; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 12px;">
                            <div class="card-header" style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; border-radius: 12px 12px 0 0;">
                                <h6 class="mb-0" style="font-weight: 800; font-size: 17px;"><i class="fas fa-user"></i> Información del Cliente</h6>
                            </div>
                            <div class="card-body" style="padding: 20px;">
                                <table class="table table-sm" style="margin-bottom: 0;">
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; width: 35%; padding: 10px 8px;">Nombre:</td>
                                        <td style="color: #212529; font-weight: 500; padding: 10px 8px;">{{ $sale->customer->nombre }} {{ $sale->customer->apellido }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Email:</td>
                                        <td style="color: #212529; font-weight: 500; padding: 10px 8px;">{{ $sale->customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Teléfono:</td>
                                        <td style="color: #212529; font-weight: 500; padding: 10px 8px;">{{ $sale->customer->telefono ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Dirección:</td>
                                        <td style="color: #212529; font-weight: 500; padding: 10px 8px;">{{ $sale->customer->direccion ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Ciudad:</td>
                                        <td style="color: #212529; font-weight: 500; padding: 10px 8px;">{{ $sale->customer->ciudad ?? 'N/A' }}, {{ $sale->customer->estado ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Tipo Cliente:</td>
                                        <td style="padding: 10px 8px;">
                                            <span class="badge" style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%); color: #C9A961; padding: 5px 12px; font-size: 13px; font-weight: 700; border-radius: 15px; border: 2px solid #8B7346;">
                                                {{ ucfirst($sale->customer->tipo_cliente ?? 'minorista') }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Venta -->
                    <div class="col-md-6">
                        <div class="card mb-3" style="border: 3px solid #8B7346; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 12px;">
                            <div class="card-header" style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%); color: #C9A961; border-radius: 12px 12px 0 0;">
                                <h6 class="mb-0" style="font-weight: 800; font-size: 17px;"><i class="fas fa-shopping-cart"></i> Información de la Venta</h6>
                            </div>
                            <div class="card-body" style="padding: 20px;">
                                <table class="table table-sm" style="margin-bottom: 0;">
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; width: 40%; padding: 10px 8px;">Folio:</td>
                                        <td style="color: #212529; font-weight: 700; padding: 10px 8px; font-size: 15px;">{{ $sale->folio }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Fecha:</td>
                                        <td style="color: #212529; font-weight: 500; padding: 10px 8px;">{{ $sale->fecha_venta->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Método de Pago:</td>
                                        <td style="padding: 10px 8px;">
                                            @if($sale->metodo_pago == 'efectivo')
                                                <span class="badge" style="background: #28a745; color: white; padding: 5px 12px; font-size: 13px; font-weight: 600; border-radius: 15px;">Efectivo</span>
                                            @elseif($sale->metodo_pago == 'tarjeta')
                                                <span class="badge" style="background: #007bff; color: white; padding: 5px 12px; font-size: 13px; font-weight: 600; border-radius: 15px;">Tarjeta</span>
                                            @elseif($sale->metodo_pago == 'transferencia')
                                                <span class="badge" style="background: #17a2b8; color: white; padding: 5px 12px; font-size: 13px; font-weight: 600; border-radius: 15px;">Transferencia</span>
                                            @else
                                                <span class="badge" style="background: #ffc107; color: #000; padding: 5px 12px; font-size: 13px; font-weight: 600; border-radius: 15px;">Crédito</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Notas:</td>
                                        <td style="color: #212529; font-weight: 500; padding: 10px 8px;">{{ $sale->notas ?? 'Sin notas' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 600; padding: 10px 8px;">Total Items:</td>
                                        <td style="padding: 10px 8px;"><span class="badge" style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; padding: 5px 12px; font-size: 13px; font-weight: 700; border-radius: 15px; border: 2px solid #D4B570;">{{ $sale->details->count() }} productos</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalle de Productos -->
                <div class="card" style="border: 3px solid #C9A961; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 12px;">
                    <div class="card-header" style="background: linear-gradient(135deg, #B8935A 0%, #8B7346 100%); color: #2C2C2C; border-radius: 12px 12px 0 0;">
                        <h6 class="mb-0" style="font-weight: 800; font-size: 17px;"><i class="fas fa-list"></i> Productos de la Venta</h6>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <thead style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%);">
                                    <tr>
                                        <th style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Código</th>
                                        <th style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Producto</th>
                                        <th style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Unidad</th>
                                        <th class="text-right" style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Cantidad</th>
                                        <th class="text-right" style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Precio Unit.</th>
                                        <th class="text-right" style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Precio Oferta</th>
                                        <th class="text-right" style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Subtotal</th>
                                        <th class="text-right" style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Descuento</th>
                                        <th class="text-right" style="color: #C9A961; font-weight: 800; padding: 12px; border: none;">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->details as $index => $detail)
                                        <tr style="{{ $index % 2 == 0 ? 'background-color: #f8f9fa;' : 'background-color: white;' }}">
                                            <td style="color: #212529; font-weight: 700; padding: 12px; vertical-align: middle;">
                                                {{ $detail->producto_codigo }}
                                            </td>
                                            <td style="color: #495057; font-weight: 600; padding: 12px; vertical-align: middle;">
                                                {{ $detail->producto_nombre }}
                                                @if($detail->product && $detail->product->imagen)
                                                    <br>
                                                    <img src="{{ asset($detail->product->imagen) }}"
                                                         alt="{{ $detail->producto_nombre }}"
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         class="rounded mt-1">
                                                @endif
                                            </td>
                                            <td style="padding: 12px; vertical-align: middle;">
                                                <span class="badge" style="background: #6c757d; color: white; padding: 5px 10px; font-size: 12px; font-weight: 600; border-radius: 12px;">
                                                    {{ ucfirst($detail->unidad_venta) }}
                                                </span>
                                            </td>
                                            <td class="text-right" style="color: #212529; font-weight: 600; padding: 12px; vertical-align: middle;">{{ $detail->cantidad }}</td>
                                            <td class="text-right" style="color: #495057; font-weight: 600; padding: 12px; vertical-align: middle;">
                                                ${{ number_format($detail->precio_unitario, 2) }}
                                            </td>
                                            <td class="text-right" style="padding: 12px; vertical-align: middle;">
                                                @if($detail->precio_oferta)
                                                    <span style="color: #28a745; font-weight: 700; font-size: 14px;">
                                                        ${{ number_format($detail->precio_oferta, 2) }}
                                                    </span>
                                                @else
                                                    <span style="color: #adb5bd;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-right" style="color: #495057; font-weight: 600; padding: 12px; vertical-align: middle;">
                                                ${{ number_format($detail->subtotal, 2) }}
                                            </td>
                                            <td class="text-right" style="padding: 12px; vertical-align: middle;">
                                                @if($detail->descuento > 0)
                                                    <span style="color: #dc3545; font-weight: 700; font-size: 14px;">
                                                        -${{ number_format($detail->descuento, 2) }}
                                                    </span>
                                                @else
                                                    <span style="color: #adb5bd;">$0.00</span>
                                                @endif
                                            </td>
                                            <td class="text-right" style="color: #212529; font-weight: 700; padding: 12px; vertical-align: middle; font-size: 15px;">
                                                ${{ number_format($detail->total, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Totales -->
                <div class="row mt-3">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="card" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 12px;">
                            <div class="card-body" style="padding: 20px;">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td style="color: #495057; font-weight: 700; padding: 10px 0; font-size: 15px;">Subtotal:</td>
                                        <td class="text-right" style="color: #212529; font-weight: 700; padding: 10px 0; font-size: 15px;">${{ number_format($sale->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 700; padding: 10px 0; font-size: 15px;">Descuento:</td>
                                        <td class="text-right" style="color: #dc3545; font-weight: 700; padding: 10px 0; font-size: 15px;">
                                            -${{ number_format($sale->descuento, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="color: #495057; font-weight: 700; padding: 10px 0; font-size: 15px;">Impuestos (IVA 16%):</td>
                                        <td class="text-right" style="color: #212529; font-weight: 700; padding: 10px 0; font-size: 15px;">${{ number_format($sale->impuestos, 2) }}</td>
                                    </tr>
                                    <tr style="background: linear-gradient(135deg, rgba(201, 169, 97, 0.15) 0%, rgba(184, 147, 90, 0.15) 100%); border-top: 3px solid #C9A961;">
                                        <td style="padding: 15px 0;"><h5 class="mb-0" style="color: #2C2C2C; font-weight: 800; font-size: 19px;">TOTAL:</h5></td>
                                        <td class="text-right" style="padding: 15px 0;">
                                            <h5 class="mb-0" style="color: #28a745; font-weight: 800; font-size: 24px; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                                                ${{ number_format($sale->total, 2) }}
                                            </h5>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 20px;">
                <button type="button" class="btn" data-dismiss="modal" wire:click="closeDetail"
                        style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%); color: #C9A961; padding: 10px 24px; font-weight: 700; border-radius: 8px; border: 2px solid #8B7346; transition: all 0.3s;">
                    <i class="fas fa-times"></i> Cerrar
                </button>
                <button type="button" class="btn" onclick="window.print()"
                        style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; padding: 10px 24px; font-weight: 700; border-radius: 8px; border: 2px solid #D4B570; box-shadow: 0 4px 15px rgba(201, 169, 97, 0.5); transition: all 0.3s;">
                    <i class="fas fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>
@endif
