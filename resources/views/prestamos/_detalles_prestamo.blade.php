@php
    $total_adeudo = 0;
    $total_recibos = 0;
    $recibos_pendientes = 0;
    $total_cargos = 0;
    foreach($prestamos as $item)
    {
        $total_cargos += $item->total_cargos;
        $total_recibos += $item->total_recibos;
        $recibos_pendientes += $item->recibos_pendientes;
    }
    $total_adeudo += $total_cargos + $total_recibos;
@endphp
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
            <label>Adeudo total:</label>
            <div class="form-control-plaintext">@money_format($total_adeudo)</div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
            <label>Adeudo en recibos:</label>
            <div class="form-control-plaintext">@money_format($total_recibos)</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
            <label>Adeudo en cargos:</label>
            <div class="form-control-plaintext">@money_format($total_cargos)</div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="form-group">
            <label>Recibos pendientes de pago:</label>
            <div class="form-control-plaintext">{{ $recibos_pendientes }}</div>
        </div>
    </div>
</div>
