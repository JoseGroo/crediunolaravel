@extends('layouts.layout_pdf')

@section('content')
<style>
    .titulo-contrato
    {
        margin-top: 0px;
        font-weight: bold;
        font-size: 11px;
    }
    .acreedor-contrato
    {
        margin-left: 120px;
        position: absolute;
    }
    .deudor-contrato
    {
        position: absolute;
        margin-left: 520px;
    }
    hr.firmas
    {
        width: 300px;

    }
    hr.firma-acreedor
    {
        position: absolute;
        top: 780px;
        left: 45px;
    }
    hr.firma-deudor
    {
        position: absolute;
        top: 780px;
        left: 450px;
    }
    .contenido-central
    {
        font-size: 11px;
    }
</style>

@php
    $total_pagar = $prestamo->total_pagos * $prestamo->importe_por_pago;
@endphp

<div class="titulo-contrato">
    <h4><center>CONTRATO DE PRESTAMO CON INTERES Y GARANTIA PRENDARIA</center></h4>
</div>
<div class="contenido-central">
    CONTRATO DE PRESTAMO CON INTERES Y GARANTIA PRENDARIA, QUE CELEBRAN POR UNA PARTE EL C. {{ $cliente->sucursal->beneficiario }}, EN SU
    CARÁCTER DE ACREDITANTE Y QUE EN LO SUCESIVO SE LE DENOMINARA “EL ACREEDOR” Y POR OTRA PARTE EL (LA) C. {{ $cliente->full_name }},
    EN SU CARÁCTER DE ACREDITADO A QUIEN EN LO SUCESIVO SE LE DENOMINARA “EL DEUDOR”, DE CONFIRMADO CON LA SIGUIENTES CLAUSULAS:
    <br><br>
    <center><strong>CLAUSULAS:</strong></center>
    <br><br>
    <strong>PRIMERA.-</strong> OBJETO DEL CONTRATO E IMPORTE DEL PRESTAMO: El objeto del presente contrato es el otorgamiento de un préstamo con interés a
    “EL DEUDOR” por la adquisición de lo que a su interés convenga por la cantidad de @money_format($total_pagar) ({{ HelperCrediuno::convertir_numero_a_letra($total_pagar) }}) para lo cual "EL ACREEDOR" prestara un importe
    que selecciono "EL DEUDOR".
    <br><br><br>
    <strong>SEGUNDA.-</strong> INTERES MORATORIO: En caso de que “EL DEUDOR” no realice el pago del capital en la fecha de vencimiento correspondiente, deberá pagar a
    “EL ACREEDOR” interés moratorio sobre el capital vencido no pagado equivalente a la tasa del 10%mensual.
    <br><br><br>
    <strong>TERCERA.-</strong> “EL DEUDOR”, realiza total disposición del crédito, mediante la suscripción de un pagare que por separado se suscribe A
    efecto de garantizar el crédito concedido, “EL DEUDOR” establece a favor de “EL ACREEDOR”, para el caso de incumplimiento una garantía prendaria
    respecto del bien mueble que a continuación se describe:
    <br><br><br>
    <!--GARANTIA SI ES QUE HAY-->
    @if($prestamo->tbl_garantia)
        {{ \App\Enums\tipos_garantia::getDescription($prestamo->tbl_garantia->tipo) }}: {{ $prestamo->tbl_garantia->descripcion }}, con un valor estimado de @money_format($prestamo->tbl_garantia->valor) pesos (MN).
        Haciendo entrega de la factura original debidamente endosada en blanco al reverso de la misma, quedando en poder de “EL ACREEDOR” el bien mueble ya descrito.
        <br><br><br>
    @endif

    <strong>CUARTA.-</strong> Convienen las partes, que al darse cumplimiento a los requisitos previstos, que establece los requisitos de constitución del derecho
    real de prenda y por contener el presente contrato deuda liquida y exigible se faculta a “EL ACREEDOR”,	el uso de la via ejecutiva, para el ejercicio del derecho real de prenda.
    <br><br><br>
    <strong>QUINTA.-</strong> AMORTIZACION: “EL DEUDOR” reconoce adeudar y por lo tanto, se obliga a pagar el Prestamo a que se refiere la Clausula Primera
    de este contrato a “EL ACREEDOR”, en moneda nacional, mas intereses moratorios cantidad que pagara directamente a “EL ACREEDOR”.
    <br><br>
    Conforme a la fecha de vencimiento prevista prevista en el titulo de credito que por separado se suscribe.
    <br><br><br>
    <strong>SEXTA.-</strong> Señala “EL DEUDOR” como domicilio para todas las notificaciones, incluyendo emplazamiento en caso de juicio, se harán en el domicilio hubicado en:
    <br><br><br>
    Calle: {{ $cliente->calle }} Entre calles: {{ $cliente->entre_calles }} Señas particulares: {{ $cliente->senas_particulares }}
    No: {{ $cliente->numero_exterior }} Colonia: {{ $cliente->colonia }} C.P: {{ $cliente->codigo_postal }} Localidad: {{ $cliente->localidad }}
    Estado: {{ $cliente->estado->estado }} País: {{ $cliente->pais }}
    <br><br>
    En la declaración segunda de este contrato.
    <br><br><br>

    <strong>SEPTIMA.-</strong> JURISDICCION: En caso de controversia respecto a la interpretación, ejecución y cumplimiento del presente contrato, las partes se
    someten a la jurisdicción de los tribunales competentes en {{ Str::upper($cliente->sucursal->ciudad->ciudad) }}, {{ Str::upper($cliente->sucursal->estado->estado) }}, haciendo renuncia expresa de cualquier otro fuero que pueda corresponderles en
    razón de sus domicilios presentes o futuros.
    <br><br><br>

    Enteradas las partes del contenido, alcance y fuerza legal del presente contrato, lo firman de conformidad, en
    {{ Str::upper($cliente->sucursal->ciudad->ciudad) }}, {{ Str::upper($cliente->sucursal->estado->estado) }}, el {{ \App\Helpers\HelperCrediuno::$nombres_dias[$date->format('l')] }} {{ $date->format('d/m/Y') }}
    <br><br><br><br>

    <div class="acreedor-contrato">
        <strong>“EL ACREEDOR”</strong>

    </div>
    <div class="deudor-contrato">
        <strong>“EL DEUDOR”</strong>
    </div>
    <hr class="firmas firma-acreedor"/>
    <hr class="firmas firma-deudor"/>
</div>

@endsection
