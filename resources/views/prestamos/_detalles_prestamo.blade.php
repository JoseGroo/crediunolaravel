@php
    $total_adeudo = 0;
    $total_recibos = 0;
    $recibos_pendientes = 0;
    $total_cargos = 0;
    foreach($prestamos as $item)
    {
        foreach ($item->tbl_adeudos as $adeudo)
        {
            if($adeudo->tbl_cargo)
                $total_cargos += $adeudo->tbl_cargo->importe_total;
        }
        $total_recibos += $item->tbl_adeudos->sum('importe_total') + $item->tbl_adeudos->sum('tbl_cargo->importe_total');
        $recibos_pendientes += $item->tbl_adeudos->count();
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