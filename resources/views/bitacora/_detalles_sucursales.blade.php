@php
    $sucursal_actuales = isset($model_actuales['sucursal']) ? $model_actuales['sucursal'] : '';
    $estado_name_actuales = isset($model_actuales['estado_name']) ? $model_actuales['estado_name'] : '';
    $ciudad_name_actuales = isset($model_actuales['ciudad_name']) ? $model_actuales['ciudad_name'] : '';
    $numero_contrato_actuales = isset($model_actuales['numero_contrato']) ? $model_actuales['numero_contrato'] : '';
    $telefono_actuales = isset($model_actuales['telefono']) ? $model_actuales['telefono'] : '';
    $beneficiario_actuales = isset($model_actuales['beneficiario']) ? $model_actuales['beneficiario'] : '';
    $direccion_actuales = isset($model_actuales['direccion']) ? $model_actuales['direccion'] : '';
    $dolar_compra_actuales = isset($model_actuales['dolar_compra']) ? $model_actuales['dolar_compra'] : '';
    $dolar_venta_actuales = isset($model_actuales['dolar_venta']) ? $model_actuales['dolar_venta'] : '';
    $euro_compra_actuales = isset($model_actuales['euro_compra']) ? $model_actuales['euro_compra'] : '';
    $euro_venta_actuales = isset($model_actuales['euro_venta']) ? $model_actuales['euro_venta'] : '';
    $dolar_moneda_compra_actuales = isset($model_actuales['dolar_moneda_compra']) ? $model_actuales['dolar_moneda_compra'] : '';
    $dolar_moneda_venta_actuales = isset($model_actuales['dolar_moneda_venta']) ? $model_actuales['dolar_moneda_venta'] : '';
    $iva_divisa_actuales = isset($model_actuales['iva_divisa']) ? $model_actuales['iva_divisa'] : '';
    
    $sucursal_anteriores = isset($model_anteriores['sucursal']) ? $model_anteriores['sucursal'] : '';
    $estado_name_anteriores = isset($model_anteriores['estado_name']) ? $model_anteriores['estado_name'] : '';
    $ciudad_name_anteriores = isset($model_anteriores['ciudad_name']) ? $model_anteriores['ciudad_name'] : '';
    $numero_contrato_anteriores = isset($model_anteriores['numero_contrato']) ? $model_anteriores['numero_contrato'] : '';
    $telefono_anteriores = isset($model_anteriores['telefono']) ? $model_anteriores['telefono'] : '';
    $beneficiario_anteriores = isset($model_anteriores['beneficiario']) ? $model_anteriores['beneficiario'] : '';
    $direccion_anteriores = isset($model_anteriores['direccion']) ? $model_anteriores['direccion'] : '';
    $dolar_compra_anteriores = isset($model_anteriores['dolar_compra']) ? $model_anteriores['dolar_compra'] : '';
    $dolar_venta_anteriores = isset($model_anteriores['dolar_venta']) ? $model_anteriores['dolar_venta'] : '';
    $euro_compra_anteriores = isset($model_anteriores['euro_compra']) ? $model_anteriores['euro_compra'] : '';
    $euro_venta_anteriores = isset($model_anteriores['euro_venta']) ? $model_anteriores['euro_venta'] : '';
    $dolar_moneda_compra_anteriores = isset($model_anteriores['dolar_moneda_compra']) ? $model_anteriores['dolar_moneda_compra'] : '';
    $dolar_moneda_venta_anteriores = isset($model_anteriores['dolar_moneda_venta']) ? $model_anteriores['dolar_moneda_venta'] : '';
    $iva_divisa_anteriores = isset($model_anteriores['iva_divisa']) ? $model_anteriores['iva_divisa'] : '';
    
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
                        $background_color_cambios = $sucursal_actuales != $sucursal_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('sucursal', 'Sucursal') }}
                            <div class="form-control-plaintext">{{ $sucursal_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $estado_name_actuales != $estado_name_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('estado', 'Estado') }}
                            <div class="form-control-plaintext">{{ $estado_name_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $ciudad_name_actuales != $ciudad_name_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('ciudad', 'Ciudad') }}
                            <div class="form-control-plaintext">{{ $ciudad_name_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $numero_contrato_actuales != $numero_contrato_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('numero_contrato', 'Número de contrato') }}
                            <div class="form-control-plaintext">{{ $numero_contrato_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $telefono_actuales != $telefono_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono') }}
                            <div class="form-control-plaintext">{{ $telefono_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $beneficiario_actuales != $beneficiario_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('beneficiario', 'Beneficiario') }}
                            <div class="form-control-plaintext">{{ $beneficiario_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $direccion_actuales != $direccion_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-12 col-sm-12 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección') }}
                            <div class="form-control-plaintext">{{ $direccion_actuales }}</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-header border-top">
                <h5 class="m-0">
                    Divisas
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    @php
                        $background_color_cambios = $dolar_compra_actuales != $dolar_compra_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('dolar_compra', 'Dolar compra') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_compra_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $dolar_venta_actuales != $dolar_venta_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('dolar_venta', 'Dolar venta') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_venta_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $euro_compra_actuales != $euro_compra_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('euro_compra', 'Euro compra') }}
                            <div class="form-control-plaintext">${{ number_format($euro_compra_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $euro_venta_actuales != $euro_venta_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('euro_venta', 'Euro venta') }}
                            <div class="form-control-plaintext">${{ number_format($euro_venta_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $dolar_moneda_compra_actuales != $dolar_moneda_compra_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('dolar_moneda_compra', 'Dolar moneda compra') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_moneda_compra_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $dolar_moneda_venta_actuales != $dolar_moneda_venta_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('dolar_moneda_venta', 'Dolar moneda venta') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_moneda_venta_actuales, 2) }}</div>
                        </div>
                    </div> 
                    
                    @php
                        $background_color_cambios = $iva_divisa_actuales != $iva_divisa_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('iva_divisa', 'IVA divisa') }}
                            <div class="form-control-plaintext">${{ number_format($iva_divisa_actuales, 2) }}</div>
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
                            {{ Form::label('sucursal', 'Sucursal') }}
                            <div class="form-control-plaintext">{{ $sucursal_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('estado', 'Estado') }}
                            <div class="form-control-plaintext">{{ $estado_name_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('ciudad', 'Ciudad') }}
                            <div class="form-control-plaintext">{{ $ciudad_name_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('numero_contrato', 'Número de contrato') }}
                            <div class="form-control-plaintext">{{ $numero_contrato_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono') }}
                            <div class="form-control-plaintext">{{ $telefono_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('beneficiario', 'Beneficiario') }}
                            <div class="form-control-plaintext">{{ $beneficiario_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección') }}
                            <div class="form-control-plaintext">{{ $direccion_anteriores }}</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-header border-top">
                <h5 class="m-0">
                    Divisas
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_compra', 'Dolar compra') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_compra_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_venta', 'Dolar venta') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_venta_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('euro_compra', 'Euro compra') }}
                            <div class="form-control-plaintext">${{ number_format($euro_compra_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('euro_venta', 'Euro venta') }}
                            <div class="form-control-plaintext">${{ number_format($euro_venta_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_moneda_compra', 'Dolar moneda compra') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_moneda_compra_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dolar_moneda_venta', 'Dolar moneda venta') }}
                            <div class="form-control-plaintext">${{ number_format($dolar_moneda_venta_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('iva_divisa', 'IVA divisa') }}
                            <div class="form-control-plaintext">${{ number_format($iva_divisa_anteriores, 2) }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>