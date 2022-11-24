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

<img class="imagen-urgente" src="{{ url('images/pdfs/carta-urgente.JPG') }}"/>
<div class="titulo-contrato">
    <strong style="font-size: 30px;">CARTA URGENTE</strong>
    <hr  />
</div>
<div class="nota-carta-urgente">
    NOTA: LE RECORDAMOS QUE SE LE BRINDO LA OPORTUNIDAD DE REESTRUCTURAR SU CUENTA, CON LO CUAL SE LE
    CONDONO EL ATRASO Y SE LE DIO LA MAYOR FACILIDAD CON SU ABONO SEMANAL, DESAFORTUNADAMENTE AL
    DIA DE HOY SU CUENTA SE ENCUENTRA EN LA MISMA SITUACIÓN, POR LO QUE EN ESTA OCASIÓN LE INFORMAMOS
    QUE DE NO PRESENTARSE EN 24 HRS. PARA TRATAR DE LLEGAR A UN ACUERDO CON SU CUENTA, ESTA SERA
    TURNADA A NUESTRO DESPACHO DE ABOGADOS, EL CUAL HARA EFECTIVO LOS DOCUMENTOS QUE USTED FIRMO
    MEDIANTE UN JUICIO EJECUTIVO MERCANTIL, CON EL CUAL SE LE EXIGIRA EL PAGO TOTAL DE SU ADEUDO EN UNA
    SOLA EXHIBICIÓN A USTED Y/O A SU AVAL A BIEN MEDIANTE UN REQUERIMIENTO DE GARANTIAS
</div>
<p class="lugar-fecha-carta-urgente">
    {{ Str::upper($cliente->sucursal->estado->estado) . ', ' . Str::upper($cliente->sucursal->ciudad->ciudad) }}
     A
    {{ $fecha_hoy }}
</p>
<div style="font-size: 15px;">
    <div class="informacion-cliente-recordatorio">

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
                <td class="numero-cuenta">NUMERO DE CUENTA:</td>
                <td><strong>{{ $cliente->cliente_id }}</strong></td>
            </tr>
            <tr>
                <td>SALDO TOTAL</td>
                <td><strong>@money_format($saldo_total)</strong></td>
            </tr>
        </table><br>
        ESTIMADO CLIENTE:
        <br /><br />
        En virtud de no haber respondido a nuestros REQUERIMIENTOS de pago anteriores, nos
        permitimos recordarle que al momento de tramitar su credito usted firmo un pagare, en los
        cuales entre otras cosas vienen las siguientes clausulas:
        <br/><br />
        El suscriptor que deje de pagar oportunamente cualquier suma, la cantidad no pagada causara
        intereses moratorios a una tasa de 15%, desde la fecha que dejo de pagar, hasta su saldo total.
        <br /><br>
        La falta de pago oportuno de cualquier exhibición producirá el vencimiento anticipado de este titulo
        por lo que esta empresa, podrá exigir el pago del saldo total de sus accesorios.
        <br/><br />
        La empresa podrá dar por vencido anticipadamente si el acreditado incumple cualesquiera de los pagos, éste
        (aval), se obliga a realizar el pago de los bienes en forma solidaria.
        <br /><br>
        @if($mostrar_direccion_sucursal)
        Nuestra intencion es ayudarle, si aun desea llegar a un acuerdo, favor de presentarse hoy mismo en
        {{ $direccion_sucursal }}, para negociar una forma de pago. Y asi evitar que su
        cuenta sea turnada al área de recuperación de Fraudes.
        @endif
        <br><br><br><br>
        <center>ATENTAMENTE </center>
        <br><br>
        <center>____________________________</center>
        <br>
        <center>GERENCIA</center>
        <br><br><br><br>

    </div>
</div>
@endsection
