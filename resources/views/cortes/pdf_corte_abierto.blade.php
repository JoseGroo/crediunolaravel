@extends('layouts.layout_pdf')

@section('content')
    @php($fecha_corte = \Carbon\Carbon::parse($corte->fecha_creacion))
    <div class="div-ticket">
        <img src="{{ url('images/crediuno.png') }}" style="width: 100%;" />

        <p class="text-center fw-bold">Crediuno {{ $user->sucursal->ciudad->ciudad }}, {{ $user->sucursal->estado->estado }}</p>

        <p class="text-center">{{ $user->sucursal->direccion }}</p>


        <div>Ventanilla No: 0000</div>
        <div>{{ $fecha_corte->format('h:i A d/m/Y') }}</div>


        <div style="width: 100%;text-align: center;border-top: 3px dashed #000;line-height: 0.301em;margin: 60px 0px 20px 0px;">
            <span style="background: #fff;  padding:100px 10px; ">Inicio de caja</span>
        </div>

        <div>
            Iniciando operaciones a las {{ $fecha_corte->format('h:i A') }} del dia {{ $fecha_corte->format('d/m/Y') }}...
        </div>
        <div>
            Empleado(a): {{ $user->nombre_completo }}
        </div>

        <div class="divider"></div>


        <div style="padding-top: 100px;">
            <div class="text-center" style="width: 45%;display:inline;float:left;">
                <hr>
                Empleado(a)
            </div>
            <div style="width: 10%;display:inline;float:left;">

            </div>
            <div class="text-center" style="width: 45%;display:inline;float:left;">
                <hr>
                Supervisor
            </div>
        </div>
    </div>
@endsection
