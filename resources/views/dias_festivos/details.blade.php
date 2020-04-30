@extends('layouts.layout')

@section("title", "Detalles del día festivo")

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
                        {{ Form::label('fecha', 'Fecha') }}
                        <div class="form-control-plaintext">{{ $model->fecha }}</div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('razon', 'Razón') }}
                        <div class="form-control-plaintext">{{ $model->razon }}</div>
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('dias_festivos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        @if($model->activo)
                            <a href="{{ route('dias_festivos.edit', $model->dia_festivo_id) }}" class="btn btn-sm btn-success">Editar</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection