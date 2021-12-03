@extends('layouts.layout_pdf')

@section('content')
    @php($fecha_creacion = \Carbon\Carbon::parse($compra_venta_divisa->fecha_creacion))
    <div class="div-ticket">

        <div>Ventanilla No: 0000</div>
        <div>{{ $fecha_creacion->format('h:i A d/m/Y') }}</div>


        <div style="width: 100%;text-align: center;border-top: 3px dashed #000;line-height: 0.301em;margin: 60px 0px 20px 0px;">
            <span style="background: #fff;  padding:100px 10px; ">{{ \App\Enums\tipo_compra_venta_divisa::getDescription($compra_venta_divisa->tipo) }}  de divisas</span>
        </div>

        <div>
            <strong>Divisa:</strong> {{ $compra_venta_divisa->tbl_divisa->divisa }}
        </div>
        <div>
            @php($tipo_cambio = ($compra_venta_divisa->importe / $compra_venta_divisa->cantidad))
            <strong>Tipo de cambio:</strong> @money_format($tipo_cambio)
        </div>
        <div>
            <strong>Cantidad:</strong> {{ number_format($compra_venta_divisa->cantidad, 0) }}
        </div>
        <div>
            <strong>Importe:</strong> @money_format($compra_venta_divisa->importe)
        </div>

        <div class="divider"></div>


        <div style="padding-top: 50px; text-align: right;">
            @if($compra_venta_divisa->tipo == \App\Enums\tipo_compra_venta_divisa::Compra)
                <strong>Entregado:</strong> @money_format($compra_venta_divisa->importe)
            @else
                <div>
                    <strong>IVA:</strong> @money_format($compra_venta_divisa->importe_iva)
                </div>
                <div>
                    <strong>Total:</strong> @money_format($compra_venta_divisa->importe)
                </div>
                <div>
                    <strong>Su pago:</strong> @money_format($compra_venta_divisa->paga_con)
                </div>
                <div>
                    <strong>Su cambio:</strong> @money_format($compra_venta_divisa->cambio)
                </div>
            @endif
        </div>
    </div>
@endsection
