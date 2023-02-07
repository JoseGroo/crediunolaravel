@extends('layouts.layout_pdf')

@section('content')
<style>
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
    .contenido-tabla
    {
        margin-top: 0px;
        margin-left: 100px;
    }
</style>

<img src="{{ asset('images/crediuno.png') }}" class="logo" width="300px" >
<div class="titulo">
    <h4>Tabla de amortizacion Fecha de la impresion: {{ $date->format('d/m/Y h:i') }}</strong></h4>
</div><hr />

<div class="contenido-tabla">
    <table class="tablaContenido amortizacion2" border="2px">
        <tr class="thead-border">
            <th>Pago</th>
            <th>Día de Pago</th>
            <th class="fecha">Fecha</th>
            <th>Pago total</th>
        </tr>

        @foreach($adeudos as $item)

            <tr>
                <td>{{ $item->numero_pago }}</td>
                <td>{{ \App\Helpers\HelperCrediuno::$nombres_dias[\Carbon\Carbon::parse($item->fecha_limite_pago)->format('l')] }}</td>
                <td>{{ \Carbon\Carbon::parse($item->fecha_limite_pago)->format('d/m/Y') }}</td>
                <td>@money_format($item->importe_total)</td>
            </tr>
        @endforeach

    </table>
</div>

@endsection
