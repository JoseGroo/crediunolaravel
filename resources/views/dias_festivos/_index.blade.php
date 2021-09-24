
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Fecha</th>
                        <th>Razón</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>
                                {{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha)->format('l')] }}
                                {{ date('d/m/Y', strtotime($item->fecha)) }}
                            </td>
                            <td>{{ $item->razon }}</td>
                            <td>
                                <a href="{{ route('dias_festivos.edit', $item->dia_festivo_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                <a href="{{ route('dias_festivos.details', $item->dia_festivo_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                <button type="button" data-dia-festivo-id="{{ $item->dia_festivo_id }}" class="btn btn-sm btn-danger delete">Eliminar</button>
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