@extends('layouts.layout_pdf')

@section('content')
    @php($fecha_creacion = \Carbon\Carbon::parse($pagos->first()->fecha_creacion))
    <div class="div-ticket">


        <img src="{{ url('images/crediuno.png') }}" style="width: 100%;" />

        <p class="text-center fw-bold">Crediuno {{ $user->sucursal->ciudad->ciudad }}, {{ $user->sucursal->estado->estado }}</p>

        <p class="text-center">{{ $user->sucursal->direccion }}</p>

        <div>Ventanilla No: 0000</div>
        <div>{{ $fecha_creacion->format('h:i A d/m/Y') }}</div>


        <div style="width: 100%;text-align: center;border-top: 3px dashed #000;line-height: 0.301em;margin: 60px 0px 20px 0px;">
            <span style="background: #fff;  padding:100px 10px; ">Recibo de pago</span>
        </div>

        <div>
            Recibimos de cliente #{{ $cliente->cliente_id }} con  domicilio en
            calle: {{ $cliente->calle }} y No: {{ $cliente->numero_exterior }}
            {{ $cliente->colonia }} {{ $cliente->localidad }}, {{ $cliente->estado->estado }}
        </div>

        <div class="pt-5">
            La cantidad de: @money_format($paga_con)
        </div>

        <div class="pt-5">

            Son: {{ $paga_con_letra }}
        </div>

        <div>
            Por concepto de abono a su cuenta
        </div>

        <div>
            Forma de pago: {{ \App\Enums\formas_pago::getDescription($pagos->first()->metodo_pago) }}
        </div>

        <div class="text-center">
            Detalle del pago/abono
        </div>

        <div>
            <table>
                <tr>
                    <td>Pagare</td>
                    <td>Recibo</td>
                    <td>Abono</td>
                    <td>Concepto</td>
                </tr>
                @foreach($pagos as $item)
                    <tr>
                        <td>{{ $item->prestamo_id }}</td>
                        <td>{{ $item->numero_pago }}</td>
                        <td>@money_format($item->importe)</td>
                        <td>{{ \App\Enums\tipo_pago::getDescription($item->tipo) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="pt-5">
            Saldo anterior: @money_format($saldo_anterior)
        </div>

        <div>
            Saldo actual: @money_format($saldo_actual)
        </div>

        <div>
            Saldo liquidación: @money_format($saldo_actual)
        </div>

        <div style="font-size: 20px!important;">
            Saldos a esta fecha
        </div>

        <div class="divider"></div>


        <div style="padding-top: 50px; text-align: right;">

            <div>
                <strong>Total:</strong> @money_format($pagos->sum('importe'))
            </div>
            <div>
                <strong>IVA:</strong> @money_format($pagos->sum('iva'))
            </div>
            <div>
                <strong>Su pago:</strong> @money_format($paga_con)
            </div>
            <div>
                <strong>Su cambio:</strong> @money_format($cambio)
            </div>
        </div>
    </div>
@endsection
