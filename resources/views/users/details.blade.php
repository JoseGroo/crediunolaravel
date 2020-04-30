@extends('layouts.layout')

@section("title", "Detalles de usuario")


@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="m-0">
                Datos
            </h5>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-3 col-sm-12 col-12">
                    @php
                        $foto_perfil =  Storage::exists($usuario->foto) ? $usuario->foto : "public/user_profile/default.png";
                    @endphp
                    <div class="form-group">
                        {{ Form::label('foto', 'Foto de perfil') }}
                    </div>

                    <a data-fancybox="gallery" href="{{ Storage::url($foto_perfil) }}">
                        <img class="img-circle img-fluid p-md" style="width: 250px; height: 250px;" src="{{ Storage::url($foto_perfil) }}">
                    </a>

                    {{--<img style="width: 100%;" src="{{ Storage::url($foto_perfil) }}">--}}
                </div>
                <div class="col-md-9 col-sm-12 col-12">

                    <div class="form-row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('nombre', 'Nombre') }}
                                <div class="form-control-plaintext">{{ $usuario->nombre }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('nombre', 'Apellido paterno') }}
                                <div class="form-control-plaintext">{{ $usuario->apellido_paterno }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('apellido_materno', 'Apellido materno') }}
                                <div class="form-control-plaintext">{{ $usuario->apellido_materno }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('rol_id', 'Rol') }}
                                <div class="form-control-plaintext">{{ $usuario->rol->rol }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('usuario', 'Usuario') }}
                                <div class="form-control-plaintext">{{ $usuario->usuario }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('sucursal_id', 'Sucursal') }}
                                <div class="form-control-plaintext">{{ $usuario->sucursal->sucursal }}</div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                {{ Form::label('direccion', 'Dirección') }}
                                <div class="form-control-plaintext">{{ $usuario->direccion }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('telefono', 'Teléfono') }}
                                <div class="form-control-plaintext">{{ $usuario->telefono }}</div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                {{ Form::label('seguro_social', 'Seguro social') }}
                                <div class="form-control-plaintext">{{ $usuario->seguro_social }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        @if($usuario->activo)
                            <a href="{{ route('users.edit', $usuario) }}" class="btn btn-sm btn-success">Editar</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection