@extends('layouts.layout')

@section("title", "Detalles del medio publicitario")

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
                        {{ Form::label('medio', 'Medio publicitario') }}
                        <div class="form-control-plaintext">{{ $model->medio_publicitario }}</div>
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('medios-publicitarios.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        @if($model->activo)
                            <a href="{{ route('medios-publicitarios.edit', $model->medio_publicitario_id) }}" class="btn btn-sm btn-success">Editar</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection