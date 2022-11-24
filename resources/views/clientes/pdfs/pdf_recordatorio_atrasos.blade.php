@extends('layouts.layout_pdf')

@section('content')
<style>
    .footer
    {
        position: absolute;
        top: 1020px;
        left: 0px;
        padding: 0px 80px 0px 80px;
    }
    .lugar-fecha-carta-urgente
    {
        position: absolute;
        top: 50px;
        right: 130px;
    }
    .contenido
    {
        font-size: 13px;
    }

    .informacion-cliente-recordatorio
    {
        margin-top:30px;
        width: 700px;
    }

    .cliente-informacion
    {
        width: 500px;
        vertical-align: top;
        display: inline-block;
    }

    .cliente-foto
    {
        width: 150px;
        vertical-align: top;
        display: inline-block;
    }

    .recordatorio-atrasos
    {
        margin-left: 50px;
    }
</style>

<div class="titulo-contrato">
    <strong style="font-size: 30px;">Recordatorio de Atrasos</strong>
    <hr  />
</div>
<p class="lugar-fecha-carta-urgente">
    {{ Str::upper($cliente->sucursal->estado->estado) . ', ' . Str::upper($cliente->sucursal->ciudad->ciudad) }}
    A
    {{ $fecha_hoy }}
</p>
<div class="contenido-recordatorio">
    <div class="informacion-cliente-recordatorio">
        <p class="pd-recordatorio-atrasos">
            P.D. SI AL MOMENTO DE RECIBIR ESTE COMUNICADO USTED HUBIERA<br />
            EFECTUADO EL PAGO, POR FAVOR HAGA CASO OMISO DE LA PRESENTE.
        </p>
        <div class="cliente-informacion">
            <h4>{{ $cliente->full_name }}</h4>
            Calle: {{ $cliente->calle }} No: {{ $cliente->numero_exterior }} Colonia: {{ $cliente->colonia }}<br />
            C.P: {{ $cliente->codigo_postal }}<br />
            {{ $cliente->localidad }}, {{ $cliente->estado->estado }}, {{ $cliente->pais }} <br><br>
        </div>
        <div class="cliente-foto">
            @if($mostrar_foto)
                {{--@php
                    $foto_perfil =  Storage::exists($cliente->foto) ? $cliente->foto : "public/user_profile/default.png";
                @endphp
                <p>{{ url($foto_perfil) }}</p>
                <img src="{{ HelperCrediuno::convert_url_to_base64(base_path($foto_perfil)) }}" width="150px" />--}}
            @endif
        </div>
    </div>
    <div class="recordatorio-atrasos">
        <table class="tabla-recordatorio-atrasos">
            <tr>
                <td>NUMERO DE CUENTA:</td>
                <td><strong>{{ $cliente->cliente_id }}</strong></td>
            </tr>
            <tr>
                <td>SALDO TOTAL</td>
                <td><strong>@money_format($saldo_total)</strong></td>
            </tr>
            <tr>
                <td>PAGOS VENCIDOS:</td>
                <td><strong>{{ $pagos_vencidos }}</strong></td>
            </tr>
        </table><br>
        ESTIMADO CLIENTE:
        <br /><br />
        LE INFORMAMOS QUE A LA FECHA DE ESTE COMUNICADO, SU CREDITO<br />
        REGISTRA {{ $pagos_vencidos }} ABONOS VENCIDOS.
        <br/><br />
        LE SOLICITAMOS ACUDIR A LA SUCURSAL MAS CERCANA A SU<br />
        DOMICILIO A FIN DE CONSULTAR EL IMPORTE DE SUS ABONOS PENDIENTES<br />
        Y CUBRIRLOS, RECUERDE QUE EL SALDO VENCIDO DE SU CREDITO YA<br />
        INCLUYE SU CARGO MORATORIO Y GASTOS DE COBRANZA GENERADOS POR<br />
        LOS ATRASOS.
        <br/><br />
        DEPARTAMENTO DE COBRANZA Y RECUPERACIÓN<br />
        @if($mostrar_telefono)
            TEL. {{ $telefonos }}<br />
        @endif
        HORARIOS DE ATENCIÓN: LUNES A VIERNES DE 10:00 AM A 06:00 PM,<br /> SABADOS DE 10:00 AM A 04:00 PM


    </div>
</div>
@endsection
