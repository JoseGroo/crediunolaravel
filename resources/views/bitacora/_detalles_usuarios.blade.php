@php
    $foto_actuales =  $model_actuales['foto'] ?? '';
    $nombre_actuales = $model_actuales['nombre'] ?? '';
    $apellido_paterno_actuales = $model_actuales['apellido_paterno'] ?? '';
    $apellido_materno_actuales = $model_actuales['apellido_materno'] ?? '';
    $rol_name_actuales = $model_actuales['rol_name'] ?? '';
    $usuario_actuales = $model_actuales['usuario'] ?? '';
    $sucursal_name_actuales = $model_actuales['sucursal_name'] ?? '';
    $direccion_actuales = $model_actuales['direccion'] ?? '';
    $telefono_actuales = $model_actuales['telefono'] ?? '';
    $seguro_social_actuales = $model_actuales['seguro_social'] ?? '';

    $foto_anteriores = $model_anteriores['foto'] ?? '';
    $nombre_anteriores = $model_anteriores['nombre'] ?? '';
    $apellido_paterno_anteriores = $model_anteriores['apellido_paterno'] ?? '';
    $apellido_materno_anteriores = $model_anteriores['apellido_materno'] ?? '';
    $rol_name_anteriores = $model_anteriores['rol_name'] ?? '';
    $usuario_anteriores = $model_anteriores['usuario'] ?? '';
    $sucursal_name_anteriores = $model_anteriores['sucursal_name'] ?? '';
    $direccion_anteriores = $model_anteriores['direccion'] ?? '';
    $telefono_anteriores = $model_anteriores['telefono'] ?? '';
    $seguro_social_anteriores = $model_anteriores['seguro_social'] ?? '';
    
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
                        $background_color_cambios = $foto_actuales != $foto_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-12 text-center {{ $background_color_cambios }}">
                        @php
                            $foto_perfil =  Storage::exists($foto_actuales) ? $foto_actuales : "public/user_profile/default.png";
                        @endphp
                        <div class="form-group text-left">
                            {{ Form::label('foto', 'Foto de perfil') }}
                        </div>

                        <a data-fancybox="gallery" href="{{ Storage::url($foto_perfil) }}">
                            <img class="img-circle img-fluid p-md" style="width: 250px; height: 250px;" src="{{ Storage::url($foto_perfil) }}">
                        </a>
                    </div>

                    @php
                        $background_color_cambios = $nombre_actuales != $nombre_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre') }}
                            <div class="form-control-plaintext">{{ $nombre_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $apellido_paterno_actuales != $apellido_paterno_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('nombre', 'Apellido paterno') }}
                            <div class="form-control-plaintext">{{ $apellido_paterno_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $apellido_materno_actuales != $apellido_materno_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('apellido_materno', 'Apellido materno') }}
                            <div class="form-control-plaintext">{{ $apellido_materno_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $rol_name_actuales != $rol_name_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('rol_id', 'Rol') }}
                            <div class="form-control-plaintext">{{ $rol_name_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $usuario_actuales != $usuario_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('usuario', 'Usuario') }}
                            <div class="form-control-plaintext">{{ $usuario_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $sucursal_name_actuales != $sucursal_name_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('sucursal_id', 'Sucursal') }}
                            <div class="form-control-plaintext">{{ $sucursal_name_actuales }}</div>
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

                    @php
                        $background_color_cambios = $telefono_actuales != $telefono_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono') }}
                            <div class="form-control-plaintext">{{ $telefono_actuales }}</div>
                        </div>
                    </div>

                    @php
                        $background_color_cambios = $seguro_social_actuales != $seguro_social_anteriores ? 'bitacora-cambio' : '';
                    @endphp
                    <div class="col-md-6 {{ $background_color_cambios }}">
                        <div class="form-group">
                            {{ Form::label('seguro_social', 'Seguro social') }}
                            <div class="form-control-plaintext">{{ $seguro_social_actuales }}</div>
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
                    <div class="col-md-12 text-center">
                        @php
                            $foto_perfil =  Storage::exists($foto_anteriores) ? $foto_anteriores : "public/user_profile/default.png";
                        @endphp
                        <div class="form-group text-left">
                            {{ Form::label('foto', 'Foto de perfil') }}
                        </div>

                        <a data-fancybox="gallery" href="{{ Storage::url($foto_perfil) }}">
                            <img class="img-circle img-fluid p-md" style="width: 250px; height: 250px;" src="{{ Storage::url($foto_perfil) }}">
                        </a>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre') }}
                            <div class="form-control-plaintext">{{ $nombre_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('nombre', 'Apellido paterno') }}
                            <div class="form-control-plaintext">{{ $apellido_paterno_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('apellido_materno', 'Apellido materno') }}
                            <div class="form-control-plaintext">{{ $apellido_materno_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('rol_id', 'Rol') }}
                            <div class="form-control-plaintext">{{ $rol_name_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('usuario', 'Usuario') }}
                            <div class="form-control-plaintext">{{ $usuario_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('sucursal_id', 'Sucursal') }}
                            <div class="form-control-plaintext">{{ $sucursal_name_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección') }}
                            <div class="form-control-plaintext">{{ $direccion_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono') }}
                            <div class="form-control-plaintext">{{ $telefono_anteriores }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('seguro_social', 'Seguro social') }}
                            <div class="form-control-plaintext">{{ $seguro_social_anteriores }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>