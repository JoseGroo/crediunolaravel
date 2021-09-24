
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->telefono }}</td>
                            <td>{{ $item->correo_electronico }}</td>
                            <td>
                                <a href="{{ route('contactos.edit', $item->contacto_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                <a href="{{ route('contactos.details', $item->contacto_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                <button type="button" data-contacto-id="{{ $item->contacto_id }}" class="btn btn-sm btn-danger delete">Eliminar</button>
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