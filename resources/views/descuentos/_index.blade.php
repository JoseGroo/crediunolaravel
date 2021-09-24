
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha de vigencia</th>
                        <th>Importe</th>
                        <th>Importe acreditado</th>
                        <th>Estatus</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->tbl_cliente->full_name }}</td>
                            <td>
                                {{ date('d/m/Y', strtotime($item->fecha_vigencia)) }}
                            </td>
                            <td>@money_format($item->importe)</td>
                            <td>@money_format($item->importe_acreditado)</td>
                            <td>{{ \App\Enums\estatus_descuentos::getDescription($item->estatus) }}</td>
                            <td>
                                <a href="{{ route('descuentos.details', $item->descuento_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                <button type="button" data-descuento-id="{{ $item->descuento_id }}" class="btn btn-sm btn-danger delete">Cancelar</button>
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