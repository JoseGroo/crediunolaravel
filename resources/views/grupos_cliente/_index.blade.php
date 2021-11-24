
@unless (empty($model))

    <div id="IndexList">

        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Grupo</th>
                        <th>Total de clientes</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)

                        <tr>
                            <td>{{ $item->grupo }}</td>
                            <td>{{ number_format($item->total_clientes, 0) }}</td>
                            <td>
                                <a href="{{ route('grupos-cliente.edit', $item->grupo_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                <a href="{{ route('grupos-cliente.details', $item->grupo_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                <button type="button" data-grupo-id="{{ $item->grupo_id }}" data-grupo="{{ $item->grupo }}" class="btn btn-sm btn-success add-client">Agregar cliente</button>
                                <button type="button" data-grupo-id="{{ $item->grupo_id }}" class="btn btn-sm btn-danger delete">Eliminar</button>
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
