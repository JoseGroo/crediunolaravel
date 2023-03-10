
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Empleado</th>
                        <th>Sucursal</th>
                        <th>Fecha</th>
                        <th>Total MN</th>
                        <th>Total USD</th>
                        <th>Total USD M</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        @php($status_color = $item->cerrado ? 'text-danger' : 'text-success')
                        <tr>
                            <td><i title="{{ $item->cerrado  ? 'Cerrado' : 'Abierto' }}" data-toogle="tooltip" class="mdi mdi-checkbox-blank-circle {{ $status_color }}"></i></td>
                            <td>{{ $item->tbl_usuario->nombre_completo }}</td>
                            <td>{{ $item->tbl_usuario->sucursal->sucursal }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->fecha_creacion)) }}</td>
                            <td>@money_format($item->importe_moneda_nacional)</td>
                            <td>@money_format($item->importe_dolares)</td>
                            <td>@money_format($item->importe_dolares_m)</td>
                            <td>
                                <a href="{{ route('cortes.details', $item->corte_id) }}" class="btn btn-sm btn-white">Detalles</a>
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
