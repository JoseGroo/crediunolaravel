@extends('layouts.layout')

@section("title", "Detalles del grupo")


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
                        {{ Form::label('grupo', 'Grupo') }}
                        <div class="form-control-plaintext">{{ $model->grupo }}</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('total_clientes', 'Total de clientes') }}
                        <div class="form-control-plaintext">{{ number_format($model->total_clientes, 0) }}</div>
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('grupos-cliente.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        <a href="{{ route('grupos-cliente.edit', $model->grupo_id) }}" class="btn btn-sm btn-success">Editar</a>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
