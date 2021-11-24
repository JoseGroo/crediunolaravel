
@unless (empty($model))

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Cliente</th>
                <th>Nombre de propietario</th>
                <th>Número de tarjeta</th>
                <th>Tipo de tarjeta</th>
                <th>Banco</th>
                <th>Importe</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($model as $item)
                <tr>
                    <td>#{{ $item->cliente_id }} - {{ $item->tbl_cliente->full_name }}</td>
                    <td>{{ $item->nombre_propietario }}</td>
                    <td>{{ $item->numero_tarjeta }}</td>
                    <td>{{ \App\Enums\tipos_tarjeta::getDescription($item->tipo) }}</td>
                    <td>{{ $item->banco }}</td>
                    <td>@money_format($item->importe_pagado)</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No hay registros.</p>
@endunless


