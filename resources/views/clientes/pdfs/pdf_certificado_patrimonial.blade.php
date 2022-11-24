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

    <img class="imagen-urgente" src="{{ url('images/pdfs/proximo-embargo.JPG') }}"/>
    <strong style="font-size: 40px;text-align: center;">CERTIFICACIÓN PATRIMONIAL</strong>
@if($mostrar_direccion_sucursal)
    <div class="footer">
        <hr />
        SE LES NOTIFICA POR ESTE CONDUCTO QUE DEVERA(N) ACUDIR AL DOMICILIO UBICADO EN {{ $direccion_sucursal }}
        PARA QUE TENGA LUGAR UNA CONCILIACIÓN.
    </div>
@endif
    <p class="lugar-fecha-carta-urgente">
        {{ Str::upper($cliente->sucursal->estado->estado) . ', ' . Str::upper($cliente->sucursal->ciudad->ciudad) }}
         A
        {{ $fecha_hoy }}
    </p>

    <div class="contenido">
        <div class="informacion-cliente-recordatorio">

            <div class="cliente-informacion">
                <h4>{{ $cliente->full_name }}</h4>
                Calle: {{ $cliente->calle }} No: {{ $cliente->numero_exterior }} Colonia: {{ $cliente->colonia }} <br>
                C.P: {{ $cliente->codigo_postal }}<br />
                {{ $cliente->localidad }}, {{ $cliente->estado->estado }}, {{ $cliente->pais }} <br><br>
            </div>
            <div class="cliente-foto">
               @if($mostrar_foto)
                   @php
                       $foto_perfil =  Storage::exists($cliente->foto) ? $cliente->foto : "public/user_profile/default.png";
                        $data = base64_decode(Storage::get($foto_perfil));

                   @endphp
                    <img src="{{ $data }}" width="150px" />
                <hr/>
                    <img src="{{ str_replace('public', 'storage', $foto_perfil) }}" width="150px" />
                @endif
            </div>
        </div>
        <div class="recordatorio-atrasos">
            <table class="tabla-recordatorio-atrasos">
                <tr>
                    <td class="numero-cuenta">NUMERO DE CUENTA:</td>
                    <td><strong>{{ $cliente->cliente_id }}</strong></td>
                </tr>
            </table><br>
            ESTIMADO CLIENTE:
            <br /><br />
            SIENDO EL DIA {{ $fecha_hoy }}, NOS CONSTITUIMOS EN SU DOMICILIO A EFECTO DE
            REALIZAR UNA "CERTIFICACIÓN PATRIMONIAL" PARA INDAGAR LA EXISTENCIA DE BIENES
            SUFICIENTES PARA CUBIR EL SALDO DE @money_format($saldo_total) CORRESPONDIENTE AL ADEUDO DE LA
            CUENTA DE NUESTRA EMPRESA. EN NUESTRA VISITA HEMOS REALIZADO LO SIGUIENTE:
            <br/><br />
            <ul>
                <li>
                    SE CERTIFICO LA EXISTENCIA DEL DOMICILIO PARA COTEJO DE LA DIRECCIÓN QUE OBRA
                    EN SU EXPEDIENTE.
                </li>
                <li>
                    SE HA DETECTADO LA EXISTENCIA DE BIENES MUEBLES E INMUEBLES.
                </li>
            </ul>
            <br /><br>
            COMO PUEDE VER HEMOS CONCLUIDO LA INTEGRACIÓN DE SU EXPEDIENTE Y ESTAMOS
            LISTOS PARA INICIAR EL PROCESO DE RECUPERACIÓN TOTAL DE SU ADEUDO, Y ESTAMOS
            DECIDIDOS A RECUPERAR ESTE CREDITO.
            <br/><br />
            LOS BIENES DETECTADOS EN NUESTRA VISITA, LA CARGA PARA DEMOSTRAR LO CONTRARIO
            ESTA EN SU CONTRA, Y TENDRA QUE HACER VALER MEDIANTE UNA TERCERA EXCLUYENTE
            DEL DOMICILIO, SE AGOTARON TODOS LOS RECURSOS PARA EJERCITAR EL DERECHO DE
            COBRO DE NUESTRA PARTE Y RECUPERAR EN SU TOTALIDAD LA CANTIDAD ADECUADA.
            CABE SEÑALAR QUE CADA UNO DE ESTOS DOCUMENTOS CUENTA CON COPIA LA CUAL SE
            INTEGRO AL EXPEDIENTE CON DOCUMENTACIÓN DONDE USTED SEÑALO ESTA DIRECCIÓN
            COMO SU DOMICILIO LEGAL Y ASI DEMOSTRAR SU NEGATIVA AL NEGOCIAR Y RESOLVER SU
            PROBLEMA DE MANERA CORDIAL Y SIN LITIS.
            <br /><br>
            SALDO TOTAL: <strong>@money_format($saldo_total)</strong>
            <br><br><br><br>
            <center>ATENTAMENTE </center>
            <br><br>
            <center>_______________________________________</center>
            <center>DIVISIÓN JURIDICA DE EMBARGOS Y<br> RECUPERACIÓN DE FRAUDES</center>

        </div>
    </div>
@endsection
