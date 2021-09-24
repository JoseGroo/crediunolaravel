@php
    $nombre_actuales = $model_actuales['nombre'] ?? '';
    $telefono_actuales = $model_actuales['telefono'] ?? '';
    $correo_electronico_actuales = $model_actuales['correo_electronico'] ?? '';
    $direccion_actuales = $model_actuales['direccion'] ?? '';


    $nombre_anteriores = $model_anteriores['nombre'] ?? '';
    $telefono_anteriores = $model_anteriores['telefono'] ?? '';
    $correo_electronico_anteriores = $model_anteriores['correo_electronico'] ?? '';
    $direccion_anteriores = $model_anteriores['direccion'] ?? '';


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
                        $background_color_cambios = $telefono_actuales != $telefono_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('telefono_actuales', 'Teléfono') }}
                            <div class="form-control-plaintext">{{ $telefono_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $correo_electronico_actuales != $correo_electronico_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('correo_electronico_actuales', 'Correo electrónico') }}
                            <div class="form-control-plaintext">{{ $correo_electronico_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $direccion_actuales != $direccion_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 col-12 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('direccion_actuales', 'Dirección') }}
                            <div class="form-control-plaintext">{{ $direccion_actuales }}</div>
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
                            {{ Form::label('telefono_anteriores', 'Teléfono') }}
                            <div class="form-control-plaintext">{{ $telefono_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('correo_electronico_anteriores', 'Correo electrónico') }}
                            <div class="form-control-plaintext">{{ $correo_electronico_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            {{ Form::label('direccion_anteriores', 'Dirección') }}
                            <div class="form-control-plaintext">{{ $direccion_anteriores }}</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>