@extends('layouts.layout_pdf')

@section('content')
<style>
    .fecha-impresion
    {
        position: absolute;
        top: 20px;
        font-size: 9px;
    }
    .clave-cliente-grande
    {
        position: absolute;
        top: 10px;
        left:670px;
        font-size: 30px;
    }
    .header-ficha-socio
    {
        text-align: center;
        margin-top: 0px;
    }
    .header-crediuno
    {
        text-align: center;
        margin-top: 0px;
    }
    .nombre-socio
    {
        margin-top: 0px;
    }
    .imagen-socio
    {
        float: left;
    }
    .datos-generales
    {
        position: absolute;
        top: 100px;
        left: 130px;
    }

    .contenido-socio
    {
        font-size: 10px;
        width: 100%;
    }
    .domicilio{
        margin-top: 130px;
    }
    .identificaciones,.contacto,.informacion-laboral,.ingresos-mensuales,.referencias,.historial-ficha-socio
    {
        margin-top: 30px;
        margin-left: 0px;
    }
    td.referencias-ficha
    {
        padding-left:-6px;
        padding-right:-6px;
    }
    td.encabezados
    {
        width:70px;
    }
    .separar-renglones
    {
        border: none;
    }
    .border
    {
        height: 2px;
    }
</style>


<div class="clave-cliente-grande"><strong>#{{ $cliente->cliente_id }}</strong></div>
<div class="fecha-impresion"><strong>Fecha: {{ $date->format('d/m/Y') }} <br>Hora: {{ $date->format('h:i') }}</strong></div>
<div class="header-ficha-socio"><strong>Ficha de Socio</strong></div>
<div class="header-crediuno">CREDIUNO {{ Str::upper($cliente->sucursal->ciudad->ciudad) }}, {{ Str::upper($cliente->sucursal->estado->estado) }}</div>
<div class="nombre-socio">Socio: <strong>{{ $cliente->full_name }}</strong></div>
<hr>
<div class="contenido-socio">
    <div class="imagen-socio">
        @php
            $url_photo = Request::root() . str_replace('public/', '/storage/', $cliente->foto)
        @endphp
        <img src="{{ $url_photo }}" width="80">
    </div>
    <div class="datos-generales">


        <table>
            <tr>
                <td colspan="4"><strong><label style="font-size: 13px;">DATOS GENERALES</label></strong></td>
            </tr>
            <tr>
                <td><strong>Sucursal:</strong></td>
                <td>{{ $cliente->sucursal->name }}</td>
                <td><strong>Fecha de Alta:</strong></td>
                <td>{{ $cliente->fecha_creacion->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Clave:</strong></td>
                <td>{{ $cliente->cliente_id }}</td>
            </tr>
            <tr>
                <td><strong>Nombre:</strong></td>
                <td colspan="2">{{ $cliente->full_name }}</td>
            </tr>
            <tr>
                <td><strong>Sexo:</strong></td>
                <td>{{ \App\Enums\sexo::getDescription($cliente->sexo) }}</td>
                <td><strong>Fecha de Nacimiento:</strong></td>
                <td>{{ $cliente->fecha_nacimiento->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>País:</strong></td>
                <td>{{ $cliente->pais }}</td>
                <td><strong>Estado Civil:</strong></td>
                <td>{{ \App\Enums\estado_civil::getDescription($cliente->estado_civil) }}</td>
            </tr>
            <tr>
                <td><strong>Ocupación:</strong></td>
                <td>{{ $cliente->ocupacion }}</td>
                <td><strong>Grupo:</strong></td>
                <td>{{ $cliente->grupo ? $cliente->grupo->grupo : 'Sin grupo asignado' }}</td>
            </tr>
        </table>
    </div>
    <div class="domicilio">
        <table>
            <tr>
                <td colspan="3"><strong><label style="font-size: 13px;">DOMICILIO</label></strong></td>
            </tr>
            <tr>
                <td><strong>Dirección:</strong></td>
                <td rowspan="2">
                        Calle: {{ $cliente->calle }} Entre calles: {{ $cliente->entre_calles }} Señas particulares: {{ $cliente->senas_particulares }}
                        No: {{ $cliente->numero_exterior }} Colonia: {{ $cliente->colonia }} C.P: {{ $cliente->codigo_postal }} Localidad: {{ $cliente->localidad }}
                        Estado: {{ $cliente->estado->estado }} País: {{ $cliente->pais }}
                </td>
                <td><strong>Antigüedad Domicilio:</strong></td>
                <td>{{ $cliente->tiempo_residencia }} {{ $cliente->tiempo_residencia == null ? '' : \App\Enums\unidad_tiempo::getDescription($cliente->unidad_tiempo_residencia) }}</td>
            </tr>
            <tr>
                <td></td>
                <td><strong>Casa:</strong></td>
                <td>{{ $cliente->vivienda }}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><strong>Renta:</strong></td>
                <td>${{ number_format($cliente->renta ?? 0, 2) }}</td>
            </tr>
        </table>
    </div>
    <div class="identificaciones">
        <table>
            <tr>
                <td colspan="3"><strong><label style="font-size: 13px;">IDENTIFICACIONES</label></strong></td>
            </tr>
            <tr>
                <td><strong>Clave Identificación:</strong></td>
                <td>{{ $cliente->clave_identificacion }}</td>

            </tr>

        </table>
    </div>
    <div class="contacto">
        <table>
            <tr>
                <td colspan="3"><strong><label style="font-size: 13px;">CONTACTO</label></strong></td>
            </tr>
            @if($cliente->tbl_informacion_contacto)
                <tr>
                    <td><strong>Teléfono Fijo:</strong></td>
                    <td>{{ $cliente->tbl_informacion_contacto->telefono_fijo }}</td>
                    <td><strong>Teléfono Movil:</strong></td>
                    <td>{{ $cliente->tbl_informacion_contacto->telefono_movil }}</td>
                </tr>
                <tr>
                    <td><strong>Telefono Alternativo1:</strong></td>
                    <td>{{ $cliente->tbl_informacion_contacto->alternativo1 }}</td>
                    <td><strong>Telefono Alternativo2:</strong></td>
                    <td>{{ $cliente->tbl_informacion_contacto->alternativo2 }}</td>
                </tr>
                <tr>
                    <td><strong>Correo:</strong></td>
                    <td>{{ $cliente->tbl_informacion_contacto->correo_electronico }}</td>
                </tr>
            @endif
        </table>
    </div>
    <div class="informacion-laboral">
        <table>
            <tr>
                <td colspan="3"><strong><label style="font-size: 13px;">INFORMACIÓN LABORAL</label></strong></td>
            </tr>
            @if($cliente->tbl_informacion_laboral)
                <tr>
                    <td><strong>Empresa:</strong></td>
                    <td>{{ $cliente->tbl_informacion_laboral->empresa }}</td>
                    <td><strong>Teléfono:</strong></td>
                    <td>{{ $cliente->tbl_informacion_laboral->telefono }}</td>
                </tr>
                <tr>
                    <td><strong>Dirección:</strong></td>
                    <td>
                        Calle: {{ $cliente->tbl_informacion_laboral->calle }} No: {{ $cliente->tbl_informacion_laboral->numero_exterior }}
                        Colonia: {{ $cliente->tbl_informacion_laboral->colonia }} C.P: {{ $cliente->tbl_informacion_laboral->codigo_postal }}
                        Localidad: {{ $cliente->tbl_informacion_laboral->localidad }} Estado: {{ $cliente->tbl_informacion_laboral->estado->estado }}
                        Pais: {{ $cliente->tbl_informacion_laboral->estado->pais }}
                    </td>
                    <td><strong>Puesto:</strong></td>
                    <td>{{ $cliente->tbl_informacion_laboral->departamento }}</td>
                </tr>
                <tr>
                    <td><strong>Jefe Inmediato:</strong></td>
                    <td>{{ $cliente->tbl_informacion_laboral->jefe_inmediato }}</td>
                    <td><strong>Antigüedad:</strong></td>
                    <td>
                        {{ $cliente->tbl_informacion_laboral->antiguedad }}
                        {{ $cliente->tbl_informacion_laboral->unidad_antiguedad == null ? '' : \App\Enums\unidad_tiempo::getDescription($cliente->tbl_informacion_laboral->unidad_antiguedad) }}
                    </td>
                </tr>
            @endif
        </table>
    </div>
    <div class="ingresos-mensuales">
        <table>
            <tr>
                <td colspan="3"><strong><label style="font-size: 13px;">INGRESOS MENSUALES</label></strong></td>
            </tr>
            @if($cliente->tbl_informacion_laboral)
                <tr>
                    <td><strong>Ingresos Propios:</strong></td>
                    <td>@money_format($cliente->tbl_informacion_laboral->ingresos_propios)</td>
                    <td><strong>Ingresos Conyuge:</strong></td>
                    <td>@money_format($cliente->tbl_informacion_laboral->ingresos_conyuge)</td>
                </tr>
                <tr>
                    <td><strong>Otros Ingresos:</strong></td>
                    <td>@money_format($cliente->tbl_informacion_laboral->otros_ingresos)</td>
                    <td><strong>Gastos Fijos:</strong></td>
                    <td>@money_format($cliente->tbl_informacion_laboral->gastos_fijos)</td>
                </tr>
                <tr>
                    <td><strong>Gastos Eventuales:</strong></td>
                    <td>@money_format($cliente->tbl_informacion_laboral->gastos_eventuales)</td>
                </tr>
            @endif
        </table>
    </div>
    <div class="historial-ficha-socio">
        <table>
            <tr>
                <td colspan="3"><strong><label style="font-size: 13px;">HISTORIAL</label></strong></td>
            </tr>
            @if($cliente->tbl_historial)
                <tr>
                    <td><strong>¿Tiene Adeudo?:</strong></td>
                    <td>{{ $cliente->tbl_historial->tiene_adeudo ? 'Si' : 'No' }}</td>
                    <td><strong>Nombre Acreedor:</strong></td>
                    <td>{{ $cliente->tbl_historial->acreedor }}</td>
                </tr>
                <tr>
                    <td><strong>Teléfono Acreedor:</strong></td>
                    <td>{{ $cliente->tbl_historial->telefono }}</td>
                    <td><strong>Adeudo:</strong></td>
                    <td>@money_format($cliente->tbl_historial->adeudo)</td>
                </tr>
                <tr>
                    <td><strong>¿Esta al corriente?:</strong></td>
                    <td>{{ $cliente->tbl_historial->esta_al_corriente ? 'Si' : 'No' }}</td>
                </tr>
            @endif
        </table>
        <div class="referencias">
            <table class="referencias-ficha">
                <tr>
                    <td colspan="3"><strong><label style="font-size: 13px;">REFERENCIAS PERSONALES</label></strong></td>
                </tr>
                @foreach ($cliente->tbl_referencias as $item)
                    <tr>
                        <td class=""><strong>Nombre:</strong></td>
                        <td class="referencias-ficha" style="width:200px;">{{ $item->full_name }}</td>
                        <td class="encabezados"><strong>Teléfono Fijo:</strong></td>
                        <td class="referencias-ficha">{{ $item->telefono_fijo }}</td>
                        <td class="encabezados"><strong>Teléfono Móvil:</strong></td>
                        <td class="referencias-ficha">{{ $item->telefono_movil }}</td>
                        <td class="encabezados"><strong>Teléfono Oficina:</strong></td>
                        <td class="referencias-ficha"class="referencias-ficha">{{ $item->telefono_oficina }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dirección:</strong></td>
                        <td rowspan="" class="referencias-ficha">
                            Calle: {{ $item->calle }} No: {{ $item->numero_exterior }}
                            Colonia: {{ $item->colonia }} C.P: {{ $item->codigo_postal }}
                            Localidad: {{ $item->localidad }} Estado: {{ $cliente->tbl_informacion_laboral->estado->estado }}
                            Pais: {{ $cliente->tbl_informacion_laboral->estado->pais }}
                        </td>
                        <td class="encabezados"><strong>Tiempo de Conocerlo:</strong></td>
                        <td class="referencias-ficha">{{ $item->tiempo_conocerlo_completo }}</td>
                        <td><strong>Relación:</strong></td>
                        <td class="referencias-ficha">{{ $item->relacion }}</td>
                    </tr>
                    <tr class="separar-renglones">
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                        <td class="border"></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
