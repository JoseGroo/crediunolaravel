
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Medio publicitario</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->medio_publicitario }}</td>
                            <td>
                                <a href="{{ route('medios-publicitarios.edit', $item->medio_publicitario_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                <a href="{{ route('medios-publicitarios.details', $item->medio_publicitario_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                <button type="button" data-medio-publicitario-id="{{ $item->medio_publicitario_id }}" class="btn btn-sm btn-danger delete">Eliminar</button>
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