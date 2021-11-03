
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Prestamo</th>
                        <th>Adeudo</th>
                        <th>Importe</th>
                        <th>Comentario</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->nombre }} {{ $item->apellido_paterno }}  {{ $item->apellido_materno }} </td>
                            <td>
                                {{ date('d/m/Y H:i:s', strtotime($item->fecha_creacion)) }}
                            </td>
                            <td>{{ $item->prestamo_id }}</td>
                            <td>{{ $item->numero_pago }}</td>
                            <td>@money_format($item->importe)</td>
                            <td>{{ $item->comentario }}</td>
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
