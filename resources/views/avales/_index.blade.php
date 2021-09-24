
@unless (empty($model))

    <div id="IndexList">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Sucursal</th>
                    <th>Domicilio</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($model as $item)
                    <tr>
                        <td>{{ $item->nombre }} {{ $item->apellido_paterno }} {{ $item->apellido_materno }}</td>
                        <td>{{ $item->sucursal }}</td>
                        <td>{{ $item->estado }}, {{ $item->localidad }}, {{ $item->colonia }}, {{ $item->calle }}</td>
                        <td>
                            <a href="{{ route('avales.details', $item->aval_id ) }}" class="btn btn-sm btn-white">Detalles</a>
                            @if(!$item->es_cliente)
                                <a href="#" class="btn btn-sm btn-info hacerlo-cliente" data-id="{{ $item->aval_id }}">Hacerlo cliente</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @include("layouts._pagination", ['model' => $model])
    </div>
@else
    <p>No se encontro información con los filtros seleccionados.</p>
@endunless