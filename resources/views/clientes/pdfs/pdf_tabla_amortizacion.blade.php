@extends('layouts.layout_pdf')

@section('content')
<style>
    td.titulo-tabla
    {
        border: 1px solid #000000;
    }

    th.fecha, td.fecha
    {
        width: 90px;
    }
    .titulo
    {
        margin-left: 350px;
        margin-top: 30px;
    }
    .logo
    {
        position: absolute;
        top: 20px;
        left: 35px;
    }
    table.amortizacion
    {
        margin-top: 130px;
        border: black 1px solid;
        border-collapse: collapse;
        padding-left: 20px;
        padding-right: 20px;
        font-size: 10px;
        margin-left:-80px;
    }
    table.amortizacion2
    {
        margin-top: 30px;
        border: black 1px solid;
        border-collapse: collapse;
        padding-left: 20px;
        padding-right: 20px;
        font-size: 10px;
        width: 700px;
        margin-left: -95px;
    }
    table.amortizacion-simulada
    {
        border: black 1px solid;
        border-collapse: collapse;
        padding-left: 20px;
        padding-right: 20px;
        font-size: 10px;
    }
    tr
    {
        padding-left: 20px;
        padding-right: 20px;
    }
    td
    {
        padding-left: 20px;
        padding-right: 20px;
    }
    thead, .thead-border
    {
        border: black 1px solid;
    }
    .contenido-columnas
    {

    }
    .columna-izquierda
    {
        font-size: 10px;
        float: left;
        margin-top: 0px;
        width: 50%;
    }
    .columna-derecha
    {
        position: absolute;
        right:20px;
        top: 120px;
        font-size: 10px;
    }
    .contenido-tabla
    {
        margin-top: 0px;
        margin-left: 100px;
    }
</style>
@php
    $total_pagar = 0;
    $clase_tabla = $para_cliente ? 'amortizacion2':'amortizacion';
@endphp
@if(!$para_cliente)
    <img src="{{ asset('images/crediuno.png') }}" class="logo" width="300px" >
    <div class="titulo">
        <h4>Tabla de amortizacion Fecha de la impresion: {{ $date->format('d/m/Y h:i') }}</strong></h4>
    </div><hr />
    <div class="columna-derecha">
        Tipo de credito: {{ $prestamo->tbl_interes->nombre }}<br>
        Periodo de pagos: {{ \App\Enums\periodos_prestamos::getDescription($prestamo->periodo) }}<br>
        Duracion del credito: {{ $prestamo->plazo }} meses<br>
        Capital Solicitado: @money_format($prestamo->capital)<br>
        Tasa Mensual: {{ $prestamo->taza_iva }}%<br>

        @if($prestamo->manejo_cuenta > 0)
            Manejo de Cuenta: @money_format($prestamo->manejo_cuenta)<br>
        @endif

        @if($prestamo->comision_apertura > 0)
            Comisión por Apertura: @money_format($prestamo->comision_apertura)<br>
        @endif

        @if($prestamo->gastos_papeleria > 0)
            Gastos de Papeleria: @money_format($prestamo->gastos_papeleria)<br>
        @endif

        @if($prestamo->gastos_cobranza > 0)
            Gastos de Cobranza: @money_format($prestamo->gastos_cobranza)<br>
        @endif

        @if($prestamo->seguro_desempleo > 0)
            Seguro por Desempleo: @money_format($prestamo->seguro_desempleo)<br>
        @endif

        @php
            $total_pagar = $prestamo->total_pagos * $prestamo->importe_por_pago;
            $primer_pago = $prestamo->tbl_adeudos[0];
            $fecha_primer_pago = \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($primer_pago->fecha_limite_pago)->format('l')] . ' '. \Carbon\Carbon::parse($primer_pago->fecha_limite_pago)->format('d/m/Y');
        @endphp
        Total a Pagar: @money_format($total_pagar)<br>
        Fecha del primer abono: {{ $fecha_primer_pago }}<br>
        @if($prestamo->periodo == \App\Enums\periodos_prestamos::Semanal)
            Dia preferido de pago: {{ \App\Enums\dias_semana::getDescription($prestamo->dia_pago) }}
        @endif

        <br>
        Genero: {{ $prestamo->tbl_usuario_genero->nombre }} {{ $prestamo->tbl_usuario_genero->apellido_paterno }} {{ $prestamo->tbl_usuario_genero->apellido_materno }}
        <br>
        Entrego:
        @if($prestamo->tbl_usuario_entrego)
            {{ $prestamo->tbl_usuario_entrego->nombre }} {{ $prestamo->tbl_usuario_entrego->apellido_paterno }} {{ $prestamo->tbl_usuario_entrego->apellido_materno }}
        @endif
    </div>
    <div class="columna-izquierda">

        Numero de Solicitud: {{$prestamo->prestamo_id}}<br>
        Clave del cliente: {{ $cliente->cliente_id }}<br>
        Nombre: {{ $cliente->full_name }}
    </div>

@endif

<div class="contenido-tabla">
    <table class="tablaContenido {{ $clase_tabla }}" border="2px">
        <tr class="thead-border">
            <th>Pago</th>
            <th>Día de Pago</th>
            <th class="fecha">Fecha</th>
            @if(!$para_cliente)
                <th>Capital</th>
                <th>Intereses</th>
            @endif
            <th>IVA</th>
            @if($para_cliente)
                <th>Abono</th>
            @endif
            @if(!$para_cliente)
                <th>Pago Total</th>
                <th>Saldo</th>
                <th>Saldo Liquidación</th>
            @endif

        </tr>

        @foreach($prestamo->tbl_adeudos as $item)
            @php
                $saldo = $total_pagar - $prestamo->importe_por_pago;
            @endphp
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}</td>
                <td>{{ \Carbon\Carbon::parse($item->fecha_limite_pago)->format('d/m/Y') }}</td>
                @if(!$para_cliente)
                    <td>@money_format($item->capital)</td>
                    <td>@money_format($item->interes)</td>
                @endif
                <td>@money_format($item->iva)</td>
                <td>@money_format($item->importe_total)</td>

                @if(!$para_cliente)
                    <td>@money_format($saldo)</td>
                    <td>@money_format($total_pagar)</td>
                @endif
            </tr>
            @php
                $total_pagar = $total_pagar - $prestamo->importe_por_pago;
            @endphp
        @endforeach


    </table>
</div>

@endsection
