@unless (empty($model))

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Cliente</th>
                <th>Banco</th>
                <th>Número de cheque</th>
                <th>Número de cuenta</th>
                <th>Importe cheque</th>
                <th>Importe pagado</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($model as $item)
                <tr>
                    <td>#{{ $item->cliente_id }} - {{ $item->tbl_cliente->full_name }}</td>
                    <td>{{ $item->banco }}</td>
                    <td>{{ $item->numero_cheque }}</td>
                    <td>{{ $item->numero_cuenta }}</td>
                    <td>@money_format($item->importe_cheque)</td>
                    <td>@money_format($item->importe_pagado)</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No hay registros.</p>
@endunless
