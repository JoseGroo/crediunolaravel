@extends('layouts.layout')

@section("title", "Detalles del contacto")

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="m-0">
                Datos
            </h5>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('nombre', 'Nombre') }}
                        <div class="form-control-plaintext">{{ $model->nombre }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('telefono', 'Teléfono') }}
                        <div class="form-control-plaintext">{{ $model->telefono }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('correo', 'Correo electrónico') }}
                        <div class="form-control-plaintext">{{ $model->correo_electronico }}</div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        {{ Form::label('direccion', 'Dirección') }}
                        <div class="form-control-plaintext">{{ $model->direccion }}</div>
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('contactos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        @if($model->activo)
                            <a href="{{ route('contactos.edit', $model->contacto_id) }}" class="btn btn-sm btn-success">Editar</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection