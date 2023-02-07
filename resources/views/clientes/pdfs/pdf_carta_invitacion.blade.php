@extends('layouts.layout_pdf')

@section('content')
<style>
    .contenido-carta-urgente
    {
        font-size: 15px;
        margin-top: 40px;
    }

    .informacion-cliente-recordatorio
    {
        margin-top:30px;
        width: 700px;
    }

    .carta-invitacion
    {
        width:600px;
        margin: 0 auto;
        font-size: 26px;
    }
    h1.nombre-invitacion
    {
        font-size: 32px;
    }
    h3.direccion-invitacion
    {
        font-size: 24px;

    }
    h1.felicidades
    {
        margin-top: 10px;
        font-size: 38px;
    }
</style>

<div class="contenido-carta-urgente">
    <img src="{{ asset('images/crediuno.png') }}" width="700px" >
    <div class="informacion-cliente-recordatorio">
        <h1 class="nombre-invitacion">{{ $cliente->full_name }}</h1>
        <h3 class="direccion-invitacion">
            Calle: {{ $cliente->calle }} No: {{ $cliente->numero_exterior }} Colonia: {{ $cliente->colonia }}
        </h3>
        <h1 class="felicidades">¡¡FELICIDADES!!</h1>

    </div>
    <div class="carta-invitacion">
        Te informamos que por haber tenido buen historial, tienes pre autorizado
        un nuevo credito con nosotros.<br><br>
        Llámanos al tel. {{ $cliente->sucursal->telefono }} o visitanos dentro de nuestro horario de lunes
        a viernes, de 10:00 am a 06:00 pm, los sabados de 10:00 am a
        04:00 pm en nuestra oficina ubicada en {{ $cliente->sucursal->direccion }}
    </div>
</div>

@endsection
