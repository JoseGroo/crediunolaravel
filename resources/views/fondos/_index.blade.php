
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Fondo</th>
                        <th>Tipo</th>
                        <th>Estatus</th>
                        <th>Importe pesos</th>
                        <th>Importe dolares</th>
                        <th>Importe dolares moneda</th>
                        <th>Importe euros</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        @php
                            $icon_color = $item->activo ? "font-active-status" : "font-inactive-status";
                            $accion_delete = $item->activo ? "Desactivar" : "Reactivar";
                            $icon_accion = $item->activo ? "mdi-delete" : "mdi-rotate-left";
                        @endphp
                        <tr>
                            <td>{{ $item->fondo }}</td>
                            <td>{{ tipo_fondo::getDescription($item->tipo) }}</td>
                            <td class="text-center"><i title="" class="mdi mdi-circle mdi-24px {{ $icon_color }}"></i></td>
                            <td>{{ number_format($item->importe_pesos, 2) }}</td>
                            <td>{{ number_format($item->importe_dolares, 2) }}</td>
                            <td>{{ number_format($item->importe_dolares_moneda, 2) }}</td>
                            <td>{{ number_format($item->importe_euros, 2) }}</td>
                            <td>
                                @if($item->activo)
                                    <a href="{{ route('fondos.edit', $item->fondo_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                @endif
                                <a href="{{ route('fondos.details', $item->fondo_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                <button type="button" fondo-id="{{ $item->fondo_id }}" class="btn btn-sm btn-danger delete">{{ $accion_delete }}</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include("layouts._pagination", ['model' => $model])
    </div>
@else
    <p>No hay registros.</p>
@endunless