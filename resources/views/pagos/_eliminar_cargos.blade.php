
@unless (empty($model))

    <div id="IndexList">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Prestamo</th>
                        <th># Recibo</th>
                        <th>Día de pago	</th>
                        <th>Fecha de pago</th>
                        <th>Importe</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($model as $item)
                        @foreach($item->tbl_cargos as $cargo)

                            <tr>
                                <td>{{ $item->folio }}</td>
                                <td>{{ $cargo->numero_pago }}</td>
                                <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($cargo->fecha_limite_pago)->format('l')] }}</td>
                                <td class="fecha-pago">{{ \Carbon\Carbon::parse($cargo->fecha_limite_pago)->format('d/m/Y') }}</td>
                                <td>@money_format($cargo->importe_total)</td>
                                <td class="text-center">
                                    <button type="button" data-cargo-id="{{ $cargo->cargo_id }}" class="btn btn-sm btn-danger delete-cargo">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@else
<!--    <p>No hay registros.</p>-->
@endunless
