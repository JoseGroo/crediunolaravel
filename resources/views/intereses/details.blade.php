@extends('layouts.layout')

@section("title", "Detalles del interes")


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

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('interes_mensual', 'Interes mensual') }}
                        <div class="form-control-plaintext">{{ $model->interes_mensual }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('interes_diario', 'Interes diario') }}
                        <div class="form-control-plaintext">{{ $model->interes_diario }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('otros_intereses', 'Otros intereses') }}
                        <div class="form-control-plaintext">{{ $model->otros_intereses }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('iva', 'IVA') }}
                        <div class="form-control-plaintext">{{ $model->iva }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('comision_apertura', 'Comisión por apertura') }}
                        <div class="form-control-plaintext">{{ $model->comision_apertura }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('manejo_cuenta', 'Manejo de cuenta') }}
                        <div class="form-control-plaintext">{{ $model->manejo_cuenta }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('gastos_papeleria', 'Gastos de papeleria') }}
                        <div class="form-control-plaintext">{{ $model->gastos_papeleria }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('gastos_cobranza', 'Gastos de cobranza') }}
                        <div class="form-control-plaintext">{{ $model->gastos_cobranza }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('seguro_desempleo', 'Seguro de desempleo') }}
                        <div class="form-control-plaintext">{{ $model->seguro_desempleo }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('iva_otros_conceptos', 'IVA otros conceptos') }}
                        <div class="form-control-plaintext">{{ $model->iva_otros_conceptos }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('imprimir_logo', 'Imprimir logo') }}
                        <div class="form-control-plaintext">{{ $model->imprimir_logo ? 'Si' : 'No' }}</div>
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('imprimir_datos_empresa', 'Imprimir datos empresa') }}
                        <div class="form-control-plaintext">{{ $model->imprimir_datos_empresa ? 'Si' : 'No' }}</div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('intereses.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        <a href="{{ route('intereses.edit', $model->interes_id) }}" class="btn btn-sm btn-success">Editar</a>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection