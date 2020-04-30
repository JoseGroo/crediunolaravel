@php
    $fecha_actuales = $model_actuales['fecha'] ?? '';
    $razon_actuales = $model_actuales['razon'] ?? '';


    $fecha_anteriores = $model_anteriores['fecha'] ?? '';
    $razon_anteriores = $model_anteriores['razon'] ?? '';


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
                        $background_color_cambios = $fecha_actuales != $fecha_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('fecha_actuales', 'Fecha') }}
                            <div class="form-control-plaintext">{{ $fecha_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $razon_actuales != $razon_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('razon_actuales', 'Razón') }}
                            <div class="form-control-plaintext">{{ $razon_actuales }}</div>
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
                            {{ Form::label('fecha_anteriores', 'Fecha') }}
                            <div class="form-control-plaintext">{{ $fecha_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('razon_anteriores', 'Razón') }}
                            <div class="form-control-plaintext">{{ $razon_anteriores }}</div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>