
<div id="IndexList">
    @if(!$model->isEmpty())

        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Movimiento</th>
                        <th>Catalago</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y h:i:s a') }}</td>
                            <td>{{ movimiento_bitacora::getDescription($item->movimiento) }}</td>
                            <td>{{ catalago_sistema::getDescription($item->catalago_sistema) }}</td>
                            <td class="text-center align-middle">
                                @switch($item->movimiento)
                                    @case(movimiento_bitacora::Edicion)
                                    <a href="#" class="btn btn-sm btn-white detalle-bitacora" bitacora-id="{{ $item->bitacora_id }}">Ver detalles</a>
                                    @break
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include("layouts._pagination", ['model' => $model])

    @else
        @include("general._not_found_data")
    @endif

</div>
