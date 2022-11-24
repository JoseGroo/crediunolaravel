
<div id="IndexList">
    @unless (empty($model))

        @php
        $total_recibos = $pagos_totales->where('importe_total', '>', 0)->count('importe_total');
        $importe_recibos = $pagos_totales->where('importe_total', '>', 0)->sum('importe_total');

        $total_cargos = $pagos_totales->where('importe_cargo', '>', 0)->count('importe_cargo');
        $importe_cargos = $pagos_totales->where('importe_cargo', '>', 0)->sum('importe_cargo');
        @endphp
        <div class="form-row">
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Recibos</h5>
                        <hr>
                        <p class="card-text text-monospace" style="font-size: 20px;">
                            {{ $total_recibos }} (@money_format($importe_recibos))
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cargos</h5>
                        <hr>
                        <p class="card-text text-monospace" style="font-size: 20px;">
                            {{ $total_cargos }} (@money_format($importe_cargos))
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total</h5>
                        <hr>
                        <p class="card-text text-monospace" style="font-size: 20px;">
                            @money_format($importe_cargos + $importe_recibos)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped" id="tableCobranza">
                <thead class="thead-dark">
                <tr>
                    <th class="text-center">
                        <div class="pretty p-icon p-curve mr-0">
                            <input type="checkbox" id="selectAll" />
                            <div class="state">
                                <i class="icon mdi mdi-check"></i>
                                <label></label>
                            </div>
                        </div>
                    </th>
                    <th>Cliente</th>
                    <th>Estatus</th>
                    <th>Prestamo</th>
                    <th>Fecha</th>
                    <th># Pago</th>
                    <th>Importe</th>
                    <th>Cargo</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td class="text-center">
                            <div class="pretty p-icon p-curve mr-0">
                                <input type="checkbox" />
                                <div class="state">
                                    <i class="icon mdi mdi-check"></i>
                                    <label></label>
                                </div>
                            </div>
                        </td>
                        <td>#{{ $item->cliente_id }} - {{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}</td>
                        <td>{{ \App\Enums\estatus_cliente::getDescription($item->estatus) }}</td>
                        <td>{{ $item->folio_prestamo }}</td>
                        <td>
                            {{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}
                            {{ date('d/m/Y', strtotime($item->fecha_limite_pago)) }}
                        </td>
                        <td>{{ $item->numero_pago }}</td>
                        <td>@money_format($item->importe_total)</td>
                        <td class="text-danger">@money_format($item->importe_cargo)</td>
                        <td>@money_format($item->importe_total + $item->importe_cargo)</td>
                        <td>
                            <a target="_blank" href="{{ route('clientes.historial', $item->cliente_id ) }}" class="btn btn-sm btn-info">Historial</a>
                            <div class="dropdown" style="display:inline-block;">
                                <a class="btn btn-sm btn-white dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-expanded="false">
                                    Descargar
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalCarta" data-cliente-id="{{ $item->cliente_id }}" data-route="{{ route('clientes.certificado_patrimonial_pdf') }}">Certificación patrimonial</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalCarta" data-cliente-id="{{ $item->cliente_id }}" data-route="{{ route('clientes.carta_urgente_pdf') }}">Carta urgente</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalCarta" data-cliente-id="{{ $item->cliente_id }}" data-route="{{ route('clientes.recordatorio_atrasos_pdf') }}">Recordatorio de atrasos</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @include("layouts._pagination", ['model' => $model])
    @endunless
</div>
