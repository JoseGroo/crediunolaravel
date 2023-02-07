@extends('layouts.layout_pdf')

@section('content')
<style>

    .titulo-pagare
    {
        margin-top: -40px;
        font-weight: bold;
        font-size: 20px;
    }
    .contenido-central-pagare
    {
        font-size: 18px;
    }
    hr.firma-suscriptor
    {
        width: 300px;
        position: absolute;
        top: 680px;
        left: 55px;
    }
    hr.firma-aval
    {
        width: 250px;
        position: absolute;
        top: 680px;
        left: 460px;
    }
    .suscriptor-pagare
    {
        top: 560px;
        margin-left: 140px;
        position: absolute;
    }
    .aval-pagare
    {
        top: 560px;
        position: absolute;
        margin-left: 540px;
    }
    .nombre-suscriptor
    {
        position: absolute;
        top: 700px;
        left: 55px;
    }
    .nombre-aval
    {
        position: absolute;
        top: 700px;
        left: 460px;
    }
</style>

<div class="titulo-pagare">
    <h4><center>PAGARE</center></h4>
</div>
@php
    $fecha_limite_pago = \Carbon\Carbon::createFromFormat('Y-m-d H:m:s', $prestamo->fecha_creacion)->addWeeks(1);
    $fecha_limite_pago = HelperCrediuno::checar_dia_festivo_y_descanso($dias_festivos, null, $fecha_limite_pago, false)['nueva_fecha'];
    $total_pagar = $prestamo->total_pagos * $prestamo->importe_por_pago;
@endphp
<div class="contenido-central-pagare">
        {{ Str::upper($cliente->sucursal->ciudad->ciudad) }}, {{ Str::upper($cliente->sucursal->estado->estado) }} a {{ $date->format('d/m/Y') }}
    <br><br>
    Por este PAGARE, debe (mos) y pagare (mos), incondicionalmente a la orden del C. {{ $cliente->sucursal->beneficiario }}, en esta Ciudad ó en cualquier
    otra que se me requiera de pago, a su vencimiento el dia {{ \App\Helpers\HelperCrediuno::$nombres_dias[$fecha_limite_pago->format('l')] }} {{ $fecha_limite_pago->format('d/m/Y') }}.
    <br><br>
    La cantidad de @money_format($total_pagar) ({{ HelperCrediuno::convertir_numero_a_letra($total_pagar) }})
    <br><br>
    Valor recibido a mi (nuestra) entera satisfacción. Este pagare es único y causara interés moratorio al tipo de 10% mensual.
    <br><br>
    Para todo lo relacionado con este pagare, el Subscriptor señala el siguiente domicilio para recibir cualquier emplazamiento
    y notificaciones legales.
    <br><br>
    Calle: {{ $cliente->calle }} Entre calles: {{ $cliente->entre_calles }} Señas particulares: {{ $cliente->senas_particulares }}
    No: {{ $cliente->numero_exterior }} Colonia: {{ $cliente->colonia }} C.P: {{ $cliente->codigo_postal }} Localidad: {{ $cliente->localidad }}
    Estado: {{ $cliente->estado->estado }} País: {{ $cliente->pais }}
    <br><br>
    @if($prestamo->tbl_aval)
        Para todo lo relacionado con este pagare, el Aval señala el siguiente domicilio para recibir cualquier emplazamiento y
        notificaciones legales.
        <br><br>
        Calle: {{ $prestamo->tbl_aval->calle }} Entre calles: {{ $prestamo->tbl_aval->entre_calles }} Señas particulares: {{ $prestamo->tbl_aval->senas_particulares }}
        No: {{ $prestamo->tbl_aval->numero_exterior }} Colonia: {{ $prestamo->tbl_aval->colonia }} C.P: {{ $prestamo->tbl_aval->codigo_postal }} Localidad: {{ $prestamo->tbl_aval->localidad }}
        Estado: {{ $prestamo->tbl_aval->estado->estado }} País: {{ $prestamo->tbl_aval->pais }}
        <br><br>
    @endif
    <br><br><br>

</div>
<div class="suscriptor-pagare">
    Suscriptor
</div>
<div class="aval-pagare">
    Aval
</div>
<div class="nombre-suscriptor">
    {{ $cliente->full_name }}
</div>
<div class="nombre-aval">
    @if($prestamo->tbl_aval)
        {{$prestamo->tbl_aval->nombre}} {{$prestamo->tbl_aval->apellido_paterno}} {{$prestamo->tbl_aval->apellido_materno}}
    @endif
</div>
<hr class="firma-suscriptor" />
<hr class="firma-aval" />

@endsection
