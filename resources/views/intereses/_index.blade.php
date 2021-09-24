
@unless (empty($model))

    <div id="IndexList">

        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Interes</th>
                        <th>Interes mensual</th>
                        <th>Interes diario</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        <tr>
                            <td>{{ $item->nombre }}</td>
                            <td class="text-center">{{ $item->interes_mensual }}%</td>
                            <td class="text-center">{{ $item->interes_diario }}%</td>
                            <td>
                                <a href="{{ route('intereses.edit', $item->interes_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                <a href="{{ route('intereses.details', $item->interes_id) }}" class="btn btn-sm btn-white">Detalles</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <p>No hay registros.</p>
@endunless
