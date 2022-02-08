@extends('layouts.layout_pdf')

@section('content')
    @php($fecha_creacion = \Carbon\Carbon::parse($model->fecha_creacion))
    <div class="div-ticket">

        <img src="{{ url('images/crediuno.png') }}" style="width: 100%;" />

        <p class="text-center fw-bold">Crediuno {{ $user->sucursal->ciudad->ciudad }}, {{ $user->sucursal->estado->estado }}</p>

        <p class="text-center">{{ $user->sucursal->direccion }}</p>

        <div>Ventanilla No: 0000</div>
        <div>{{ $fecha_creacion->format('h:i A d/m/Y') }}</div>


        <div style="width: 100%;text-align: center;border-top: 3px dashed #000;line-height: 0.301em;margin: 60px 0px 20px 0px;">
            <span style="background: #fff;  padding:100px 10px; ">Venta otro concepto</span>
        </div>


        <div>
            <strong>Produucto:</strong> {{ $model->concepto }}
        </div>
        <div>
            <strong>Importe:</strong> @money_format($model->importe)
        </div>

        <div class="divider"></div>


        <div style="padding-top: 50px; text-align: right;">

            <div>
                <strong>Total:</strong> @money_format($model->importe)
            </div>
            <div>
                <strong>Su pago:</strong> @money_format($model->paga_con)
            </div>
            <div>
                <strong>Su cambio:</strong> @money_format($model->cambio)
            </div>
        </div>
    </div>
@endsection
