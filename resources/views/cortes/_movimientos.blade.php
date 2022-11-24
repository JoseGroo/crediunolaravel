
<div class="row mb-3">
    <div class="col-12">
        <button type="button" id="btnReimprimir" class="btn btn-sm btn-info">Imprimir</button>
        @if(!$corte->cerrado)
            <button type="button" id="btnCancelar" class="btn btn-sm btn-danger">Cancelar</button>
        @endif
    </div>
</div>

<div class="table-responsive m-t-lg table-fix-head">

    <table class="table table-hover">
        <thead>
        <tr>
            <th style="z-index: 2;"></th>
            <th># Mov</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th>Importe</th>
            <th>Forma de pago</th>
        </tr>
        </thead>
        <tbody>
        @if($model && !$model->isEmpty())
            @foreach ($model as $item)
                @php
                    $movimientos_con_reimpresion = [
                        \App\Enums\tipos_movimientos_corte::PagosCargos,
                        \App\Enums\tipos_movimientos_corte::PagosRecibos,
                        \App\Enums\tipos_movimientos_corte::CobroOtroConcepto,
                        \App\Enums\tipos_movimientos_corte::CompraDivisa,
                        \App\Enums\tipos_movimientos_corte::VentaDivisa
                    ];

                    $movimientos_con_cancelacion = [
                        \App\Enums\tipos_movimientos_corte::PagosCargos,
                        \App\Enums\tipos_movimientos_corte::PagosRecibos,
                        \App\Enums\tipos_movimientos_corte::CobroOtroConcepto,
                        \App\Enums\tipos_movimientos_corte::CompraDivisa,
                        \App\Enums\tipos_movimientos_corte::VentaDivisa,
                        \App\Enums\tipos_movimientos_corte::EntregaPrestamo
                    ];
                    $checkbox = in_array($item->tipo, array_merge($movimientos_con_reimpresion, $movimientos_con_cancelacion)) && $item->estatus == \App\Enums\estatus_movimientos_corte::Activo;
                    $can_reprint = in_array($item->tipo, $movimientos_con_reimpresion) && $item->estatus == \App\Enums\estatus_movimientos_corte::Activo;
                    $canceled = $item->estatus == \App\Enums\estatus_movimientos_corte::Cancelado ? "bg-danger text-white" : ""
                @endphp
                <tr class="{{ $canceled }}">
                    <td class="text-center">
                        @if($checkbox)
                            <div style="line-height: 1.23;" class="checkbox checkbox-success" data-tipo="{{ $item->tipo }}">
                                <input type="checkbox" data-can-reprint="{{ $can_reprint }}" style="position: relative; margin-left: -10px;" data-type="{{ $item->tipo }}" data-id="{{ $item->movimiento_corte_id }}" class="check-box-value item-reprint-cancel" >
                                <label></label>
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->movimiento_corte_id }}</td>
                    <td>{{ Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y h:i:s a') }}</td>
                    <td>
                        @if($item->cliente_id)
                            #{{ $item->cliente_id }} - {{ $item->nombre }} {{ $item->apellido_paterno }}
                        @endif
                    </td>
                    <td>{{ \App\Enums\tipos_movimientos_corte::getDescription($item->tipo) }}</td>
                    <td>
                        @if($item->tipo != \App\Enums\tipos_movimientos_corte::AltaCliente)
                            @money_format($item->importe)
                        @endif
                    </td>
                    <td>
                        @if($item->tipo != \App\Enums\tipos_movimientos_corte::AltaCliente)
                            {{ \App\Enums\formas_pago::getDescription($item->metodo_pago) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10">No hay movimientos registrados.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<div class="row mt-3">
    <div class="col-12 mb-2">
        <h5 class="m-0">Desglose de movimientos</h5>
    </div>
    @php
        $model = $model->where('estatus', '=', \App\Enums\estatus_movimientos_corte::Activo);
        $total_altas_clientes = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::AltaCliente)->count();

        $total_prestamos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::EntregaPrestamo)->count();
        $importe_prestamos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::EntregaPrestamo)->sum('importe');

        $total_cobros = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::PagosCargos)
                              ->whereIn('metodo_pago', [\App\Enums\formas_pago::Efectivo, \App\Enums\formas_pago::Refinanciar, \App\Enums\formas_pago::Retencion])
                              ->count();
        $total_cobros += $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::PagosRecibos)
                               ->whereIn('metodo_pago', [\App\Enums\formas_pago::Efectivo, \App\Enums\formas_pago::Refinanciar, \App\Enums\formas_pago::Retencion])
                               ->count();

        $total_dolares_vendidos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::VentaDivisa)
                                        ->where('divisa_id', '=', 1)->sum('cantidad_divisa');
        $importe_dolares_vendidos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::VentaDivisa)
                                        ->where('divisa_id', '=', 1)->sum('importe');

        $total_dolares_comprados = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CompraDivisa)
                                         ->where('divisa_id', '=', 1)->sum('cantidad_divisa');
        $importe_dolares_comprados = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CompraDivisa)
                                         ->where('divisa_id', '=', 1)->sum('importe');

        $total_dolares_monedas_vendidos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::VentaDivisa)
                                        ->where('divisa_id', '=', 2)->sum('cantidad_divisa');
        $importe_dolares_monedas_vendidos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::VentaDivisa)
                                        ->where('divisa_id', '=', 2)->sum('importe');

        $total_dolares_monedas_comprados = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CompraDivisa)
                                         ->where('divisa_id', '=', 2)->sum('cantidad_divisa');
        $importe_dolares_monedas_comprados = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CompraDivisa)
                                         ->where('divisa_id', '=', 2)->sum('importe');

        $total_euros_vendidos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::VentaDivisa)
                                        ->where('divisa_id', '=', 3)->sum('cantidad_divisa');
        $importe_euros_vendidos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::VentaDivisa)
                                        ->where('divisa_id', '=', 3)->sum('importe');

        $total_euros_comprados = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CompraDivisa)
                                         ->where('divisa_id', '=', 3)->sum('cantidad_divisa');
        $importe_euros_comprados = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CompraDivisa)
                                         ->where('divisa_id', '=', 3)->sum('importe');

        $total_otros_conceptos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CobroOtroConcepto)->count();
        $importe_otros_conceptos = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::CobroOtroConcepto)->sum('importe');

        $importe_operaciones = $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::PagosRecibos)
                                     ->whereIn('metodo_pago', [\App\Enums\formas_pago::Efectivo, \App\Enums\formas_pago::Refinanciar, \App\Enums\formas_pago::Retencion])
                                     ->sum('importe');
        $importe_operaciones += $model->where('tipo', '=', \App\Enums\tipos_movimientos_corte::PagosCargos)
                                      ->whereIn('metodo_pago', [\App\Enums\formas_pago::Efectivo, \App\Enums\formas_pago::Refinanciar, \App\Enums\formas_pago::Retencion])
                                      ->sum('importe');

        $total_transferencias = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Transferencia)->count();
        $importe_transferencias = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Transferencia)->sum('importe');

        $total_retiros = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Retiro)->count();
        $importe_retiros = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Retiro)->sum('importe');
    @endphp
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Operaciones</h5>
                <hr>
                <p class="card-text text-monospace">{{ $model->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Altas de clientes</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_altas_clientes }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Prestamos entregados</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_prestamos }}(@money_format($importe_prestamos))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Cobros</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_cobros }}(@money_format($importe_operaciones))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Traspasos</h5>
                <hr>
                <a class="card-text text-monospace text-info" data-toggle="modal" data-target="#modalTotalesTraspasos">Ver totales</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Retiros</h5>
                <hr>
                <a class="card-text text-monospace text-info" data-toggle="modal" data-target="#modalTotalesRetiros">Ver totales</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Dolares vendidos</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_dolares_vendidos }}(@money_format($importe_dolares_vendidos))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Dolares comprados</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_dolares_comprados }}(@money_format($importe_dolares_comprados))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Dolares moneda vendidos</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_dolares_monedas_vendidos }}(@money_format($importe_dolares_monedas_vendidos))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Dolares moneda comprados</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_dolares_monedas_comprados }}(@money_format($importe_dolares_monedas_comprados))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Euros vendidos</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_euros_vendidos }}(@money_format($importe_euros_vendidos))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Euros comprados</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_euros_comprados }}(@money_format($importe_euros_comprados))</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6  mt-2">
        <div class="card card-cortes-totales">
            <div class="card-body text-center">
                <h5 class="card-title">Otros conceptos</h5>
                <hr>
                <p class="card-text text-monospace">{{ $total_otros_conceptos }}(@money_format($importe_otros_conceptos))</p>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal" id="modalTotalesTraspasos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInUp">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Total de traspasos por divisa</h4>
            </div>
            <div class="modal-body">
               <div class="row">
                   @foreach($divisas as $item)
                       @php
                           $total_divisa = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Transferencia)
                                            ->where('divisa_id', '=', $item->divisa_id)->count();
                           $importe_divisa = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Transferencia)
                                            ->where('divisa_id', '=', $item->divisa_id)->sum('importe');
                       @endphp
                       <div class="col-md-6 col-sm-12 mt-2">
                           <div class="card card-cortes-totales">
                               <div class="card-body text-center">
                                   <h5 class="card-title">{{ $item->divisa }}</h5>
                                   <hr>
                                   <p class="card-text text-monospace">{{ $total_divisa }}(@money_format($importe_divisa))</p>
                               </div>
                           </div>
                       </div>
                   @endforeach
               </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal inmodal" id="modalTotalesRetiros" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInUp">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Total de retiros por divisa</h4>
            </div>
            <div class="modal-body">
               <div class="row">
                   @foreach($divisas as $item)
                       @php
                           $total_divisa = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Retiro)
                                            ->where('divisa_id', '=', $item->divisa_id)->count();
                           $importe_divisa = $transferencias_rertiros->where('tipo', '=', \App\Enums\tipo_transferencia_fondo::Retiro)
                                            ->where('divisa_id', '=', $item->divisa_id)->sum('importe');
                       @endphp
                       <div class="col-md-6 col-sm-12 mt-2">
                           <div class="card card-cortes-totales">
                               <div class="card-body text-center">
                                   <h5 class="card-title">{{ $item->divisa }}</h5>
                                   <hr>
                                   <p class="card-text text-monospace">{{ $total_divisa }}(@money_format($importe_divisa))</p>
                               </div>
                           </div>
                       </div>
                   @endforeach
               </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
