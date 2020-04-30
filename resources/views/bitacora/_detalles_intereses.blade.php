@php
    $nombre_actuales = isset($model_actuales['nombre']) ? $model_actuales['nombre'] : '';
    $interes_mensual_actuales = isset($model_actuales['interes_mensual']) ? $model_actuales['interes_mensual'] : '';
    $interes_diario_actuales = isset($model_actuales['interes_diario']) ? $model_actuales['interes_diario'] : '';
    $otros_intereses_actuales = isset($model_actuales['otros_intereses']) ? $model_actuales['otros_intereses'] : '';
    $iva_actuales = isset($model_actuales['iva']) ? $model_actuales['iva'] : '';
    $comision_apertura_actuales = isset($model_actuales['comision_apertura']) ? $model_actuales['comision_apertura'] : '';
    $manejo_cuenta_actuales = isset($model_actuales['manejo_cuenta']) ? $model_actuales['manejo_cuenta'] : '';
    $gastos_papeleria_actuales = isset($model_actuales['gastos_papeleria']) ? $model_actuales['gastos_papeleria'] : '';
    $gastos_cobranza_actuales = isset($model_actuales['gastos_cobranza']) ? $model_actuales['gastos_cobranza'] : '';
    $seguro_desempleo_actuales = isset($model_actuales['seguro_desempleo']) ? $model_actuales['seguro_desempleo'] : '';
    $iva_otros_conceptos_actuales = isset($model_actuales['iva_otros_conceptos']) ? $model_actuales['iva_otros_conceptos'] : '';
    $imprimir_logo_actuales = isset($model_actuales['imprimir_logo']) ? $model_actuales['imprimir_logo'] : '';
    $imprimir_datos_empresa_actuales = isset($model_actuales['imprimir_datos_empresa']) ? $model_actuales['imprimir_datos_empresa'] : '';

    $nombre_anteriores = isset($model_anteriores['nombre']) ? $model_anteriores['nombre'] : '';
    $interes_mensual_anteriores = isset($model_anteriores['interes_mensual']) ? $model_anteriores['interes_mensual'] : '';
    $interes_diario_anteriores = isset($model_anteriores['interes_diario']) ? $model_anteriores['interes_diario'] : '';
    $otros_intereses_anteriores = isset($model_anteriores['otros_intereses']) ? $model_anteriores['otros_intereses'] : '';
    $iva_anteriores = isset($model_anteriores['iva']) ? $model_anteriores['iva'] : '';
    $comision_apertura_anteriores = isset($model_anteriores['comision_apertura']) ? $model_anteriores['comision_apertura'] : '';
    $manejo_cuenta_anteriores = isset($model_anteriores['manejo_cuenta']) ? $model_anteriores['manejo_cuenta'] : '';
    $gastos_papeleria_anteriores = isset($model_anteriores['gastos_papeleria']) ? $model_anteriores['gastos_papeleria'] : '';
    $gastos_cobranza_anteriores = isset($model_anteriores['gastos_cobranza']) ? $model_anteriores['gastos_cobranza'] : '';
    $seguro_desempleo_anteriores = isset($model_anteriores['seguro_desempleo']) ? $model_anteriores['seguro_desempleo'] : '';
    $iva_otros_conceptos_anteriores = isset($model_anteriores['iva_otros_conceptos']) ? $model_anteriores['iva_otros_conceptos'] : '';
    $imprimir_logo_anteriores = isset($model_anteriores['imprimir_logo']) ? $model_anteriores['imprimir_logo'] : '';
    $imprimir_datos_empresa_anteriores = isset($model_anteriores['imprimir_datos_empresa']) ? $model_anteriores['imprimir_datos_empresa'] : '';

@endphp

<div class="form-row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">
                    Datos actuales
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    @php
                        $background_color_cambios = $nombre_actuales != $nombre_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre') }}
                            <div class="form-control-plaintext">{{ $nombre_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $interes_mensual_actuales != $interes_mensual_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('interes_mensual', 'Interes mensual') }}
                            <div class="form-control-plaintext">{{ $interes_mensual_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $interes_diario_actuales != $interes_diario_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('interes_diario', 'Interes diario') }}
                            <div class="form-control-plaintext">{{ $interes_diario_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $otros_intereses_actuales != $otros_intereses_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('otros_intereses_', 'Otros intereses') }}
                            <div class="form-control-plaintext">{{ $otros_intereses_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $iva_actuales != $iva_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('iva', 'IVA') }}
                            <div class="form-control-plaintext">{{ $iva_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $comision_apertura_actuales != $comision_apertura_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('comision_apertura', 'Comisión por apertura') }}
                            <div class="form-control-plaintext">{{ $comision_apertura_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $manejo_cuenta_actuales != $manejo_cuenta_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('manejo_cuenta', 'Manejo de cuenta') }}
                            <div class="form-control-plaintext">{{ $manejo_cuenta_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $gastos_papeleria_actuales != $gastos_papeleria_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('gastos_papeleria', 'Gastos de papeleria') }}
                            <div class="form-control-plaintext">{{ $gastos_papeleria_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $gastos_cobranza_actuales != $gastos_cobranza_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('gastos_cobranza', 'Gastos de cobranza') }}
                            <div class="form-control-plaintext">{{ $gastos_cobranza_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $seguro_desempleo_actuales != $seguro_desempleo_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('seguro_desempleo', 'Seguro de desempleo') }}
                            <div class="form-control-plaintext">{{ $seguro_desempleo_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $iva_otros_conceptos_actuales != $iva_otros_conceptos_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('iva_otros_conceptos_actuales', 'IVA otros conceptos') }}
                            <div class="form-control-plaintext">{{ $iva_otros_conceptos_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $imprimir_logo_actuales != $imprimir_logo_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('imprimir_logo', 'Imprimir logo') }}
                            <div class="form-control-plaintext">{{ $imprimir_logo_actuales ? 'Si' : 'No' }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $imprimir_datos_empresa_actuales != $imprimir_datos_empresa_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('imprimir_datos_empresa', 'Imprimir datos empresa') }}
                            <div class="form-control-plaintext">{{ $imprimir_datos_empresa_actuales ? 'Si' : 'No' }}</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">
                    Datos anteriores
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre') }}
                            <div class="form-control-plaintext">{{ $nombre_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('interes_mensual', 'Interes mensual') }}
                            <div class="form-control-plaintext">{{ $interes_mensual_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('interes_diario', 'Interes diario') }}
                            <div class="form-control-plaintext">{{ $interes_diario_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('otros_intereses_anteriores', 'Otros intereses') }}
                            <div class="form-control-plaintext">{{ $otros_intereses_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('iva_anteriores', 'IVA') }}
                            <div class="form-control-plaintext">{{ $iva_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('comision_apertura_anteriores', 'Comisión por apertura') }}
                            <div class="form-control-plaintext">{{ $comision_apertura_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('manejo_cuenta_anteriores', 'Manejo de cuenta') }}
                            <div class="form-control-plaintext">{{ $manejo_cuenta_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('gastos_papeleria_anteriores', 'Gastos de papeleria') }}
                            <div class="form-control-plaintext">{{ $gastos_papeleria_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('gastos_cobranza_anteriores', 'Gastos de cobranza') }}
                            <div class="form-control-plaintext">{{ $gastos_cobranza_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('seguro_desempleo_anteriores', 'Seguro de desempleo') }}
                            <div class="form-control-plaintext">{{ $seguro_desempleo_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('iva_otros_conceptos_anteriores', 'IVA otros conceptos') }}
                            <div class="form-control-plaintext">{{ $iva_otros_conceptos_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('imprimir_logo_anteriores', 'Imprimir logo') }}
                            <div class="form-control-plaintext">{{ $imprimir_logo_anteriores ? 'Si' : 'No' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('imprimir_datos_empresa_anteriores', 'Imprimir datos empresa') }}
                            <div class="form-control-plaintext">{{ $imprimir_datos_empresa_anteriores ? 'Si' : 'No' }}</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>