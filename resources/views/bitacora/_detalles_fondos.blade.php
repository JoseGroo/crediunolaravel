@php
    $fondo_actuales = $model_actuales['fondo'] ?? '';
    $tipo_actuales = $model_actuales['tipo'] ?? '';
    $banco_actuales = $model_actuales['banco'] ?? '';
    $cuenta_actuales = $model_actuales['cuenta'] ?? '';
    $ultimo_cheque_girado_actuales = $model_actuales['ultimo_cheque_girado'] ?? '';
    $importe_pesos_actuales = $model_actuales['importe_pesos'] ?? '';
    $importe_dolares_actuales = $model_actuales['importe_dolares'] ?? '';
    $importe_dolares_moneda_actuales = $model_actuales['importe_dolares_moneda'] ?? '';
    $importe_euros_actuales = $model_actuales['importe_euros'] ?? '';


    $fondo_anteriores = $model_anteriores['fondo'] ?? '';
    $tipo_anteriores = $model_anteriores['tipo'] ?? '';
    $banco_anteriores = $model_anteriores['banco'] ?? '';
    $cuenta_anteriores = $model_anteriores['cuenta'] ?? '';
    $ultimo_cheque_girado_anteriores = $model_anteriores['ultimo_cheque_girado'] ?? '';
    $importe_pesos_anteriores = $model_anteriores['importe_pesos'] ?? '';
    $importe_dolares_anteriores = $model_anteriores['importe_dolares'] ?? '';
    $importe_dolares_moneda_anteriores = $model_anteriores['importe_dolares_moneda'] ?? '';
    $importe_euros_anteriores = $model_anteriores['importe_euros'] ?? '';


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
                        $background_color_cambios = $fondo_actuales != $fondo_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('fondo', 'Fondo') }}
                            <div class="form-control-plaintext">{{ $fondo_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $tipo_actuales != $tipo_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('tipo_actuales', 'Tipo') }}
                            <div class="form-control-plaintext">{{ tipo_fondo::getDescription($tipo_actuales) }}</div>
                        </div>
                    </div>

                    @if($tipo_actuales == tipo_fondo::Bancario)
                        @php
                            $background_color_cambios = $banco_actuales != $banco_anteriores ? 'bitacora-cambio' : '';
                        @endphp
                        <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                            <div class="form-group">
                                {{ Form::label('banco_actuales', 'Banco') }}
                                <div class="form-control-plaintext">{{ $banco_actuales }}</div>
                            </div>
                        </div>

                        @php
                            $background_color_cambios = $cuenta_actuales != $cuenta_anteriores ? 'bitacora-cambio' : '';
                        @endphp
                        <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                            <div class="form-group">
                                {{ Form::label('cuenta_actuales', 'Cuenta') }}
                                <div class="form-control-plaintext">{{ $cuenta_actuales }}</div>
                            </div>
                        </div>

                        @php
                            $background_color_cambios = $ultimo_cheque_girado_actuales != $ultimo_cheque_girado_anteriores ? 'bitacora-cambio' : '';
                        @endphp
                        <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                            <div class="form-group">
                                {{ Form::label('ultimo_cheque_girado_actuales', 'Ultimo cheque girado') }}
                                <div class="form-control-plaintext">{{ $ultimo_cheque_girado_actuales }}</div>
                            </div>
                        </div>
                    @endif

                    @php
                        $background_color_cambios = $importe_pesos_actuales != $importe_pesos_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('importe_pesos_actuales', 'Importe en pesos') }}
                            <div class="form-control-plaintext">{{ number_format($importe_pesos_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $importe_dolares_actuales != $importe_dolares_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('importe_dolares_actuales', 'Importe en dolares') }}
                            <div class="form-control-plaintext">{{ number_format($importe_dolares_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $importe_dolares_moneda_actuales != $importe_dolares_moneda_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('importe_dolares_moneda_actuales', 'Importe en dolares moneda') }}
                            <div class="form-control-plaintext">{{ number_format($importe_dolares_moneda_actuales, 2) }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $importe_euros_actuales != $importe_euros_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('importe_euros_actuales', 'Importe en euros') }}
                            <div class="form-control-plaintext">{{ number_format($importe_euros_actuales, 2) }}</div>
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
                            {{ Form::label('fondo', 'Fondo') }}
                            <div class="form-control-plaintext">{{ $fondo_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('tipo_anteriores', 'Tipo') }}
                            <div class="form-control-plaintext">{{ tipo_fondo::getDescription($tipo_anteriores) }}</div>
                        </div>
                    </div>

                    @if($tipo_anteriores == tipo_fondo::Bancario)
                        
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('banco_anteriores', 'Banco') }}
                                <div class="form-control-plaintext">{{ $banco_anteriores }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('cuenta_anteriores', 'Cuenta') }}
                                <div class="form-control-plaintext">{{ $cuenta_anteriores }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('ultimo_cheque_girado_anteriores', 'Ultimo cheque girado') }}
                                <div class="form-control-plaintext">{{ $ultimo_cheque_girado_anteriores }}</div>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('importe_pesos_anteriores', 'Importe en pesos') }}
                            <div class="form-control-plaintext">{{ number_format($importe_pesos_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('importe_dolares_anteriores', 'Importe en dolares') }}
                            <div class="form-control-plaintext">{{ number_format($importe_dolares_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('importe_dolares_moneda_anteriores', 'Importe en dolares moneda') }}
                            <div class="form-control-plaintext">{{ number_format($importe_dolares_moneda_anteriores, 2) }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('importe_euros_anteriores', 'Importe en euros') }}
                            <div class="form-control-plaintext">{{ number_format($importe_euros_anteriores, 2) }}</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>