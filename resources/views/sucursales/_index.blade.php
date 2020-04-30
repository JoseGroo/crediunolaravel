
@unless (empty($model))

    <div id="IndexList">

        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Sucursal</th>
                        <th>Estado</th>
                        <th>Ciudad</th>
                        <th>Beneficiario</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->sucursal }}</td>
                            <td>{{ $item->estado->estado }}</td>
                            <td>{{ $item->ciudad->ciudad }}</td>
                            <td>{{ $item->beneficiario }}</td>
                            <td>
                                <a href="{{ route('sucursales.edit', $item->sucursal_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                <a href="{{ route('sucursales.details', $item->sucursal_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                <button type="button" sucursal-id="{{ $item->sucursal_id }}" class="btn btn-sm btn-danger delete">Eliminar</button>
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