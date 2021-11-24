
@unless (empty($model))

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
            <tr>
                <th>Cliente</th>
                <th>Banco</th>
                <th>Cueta receptora</th>
                <th>Cuentahabiente</th>
                <th>Importe ficha</th>
                <th>Importe pagado</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($model as $item)
                <tr>
                    <td>#{{ $item->cliente_id }} - {{ $item->tbl_cliente->full_name }}</td>
                    <td>{{ $item->banco }}</td>
                    <td>{{ $item->cuenta_receptora }}</td>
                    <td>{{ $item->cuentahabiente }}</td>
                    <td>@money_format($item->importe_ficha_deposito)</td>
                    <td>@money_format($item->importe_pagado)</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No hay registros.</p>
@endunless


