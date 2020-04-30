@extends('layouts.layout')

@section("title", "Editar interes")


@section('content')

    @include('general._errors')

    {{ Form::open([ 'route' => ['intereses.edit_post' ], 'method' => 'POST', 'id' => 'frmEdit' ]) }}

    {{ Form::hidden('interes_id', $model->interes_id) }}

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
                        {{ Form::text('nombre', $model->nombre, [ 'class' => 'form-control', 'autofocus' => true ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('interes_mensual', 'Interes mensual') }}
                        {{ Form::text('interes_mensual', $model->interes_mensual, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('interes_diario', 'Interes diario') }}
                        {{ Form::text('interes_diario', $model->interes_diario, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('otros_intereses', 'Otros intereses') }}
                        {{ Form::text('otros_intereses', $model->otros_intereses, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('iva', 'IVA') }}
                        {{ Form::text('iva', $model->iva, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('comision_apertura', 'Comisión por apertura') }}
                        {{ Form::text('comision_apertura', $model->comision_apertura, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('manejo_cuenta', 'Manejo de cuenta') }}
                        {{ Form::text('manejo_cuenta', $model->manejo_cuenta, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('gastos_papeleria', 'Gastos de papeleria') }}
                        {{ Form::text('gastos_papeleria', $model->gastos_papeleria, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('gastos_cobranza', 'Gastos de cobranza') }}
                        {{ Form::text('gastos_cobranza', $model->gastos_cobranza, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('seguro_desempleo', 'Seguro de desempleo') }}
                        {{ Form::text('seguro_desempleo', $model->seguro_desempleo, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('iva_otros_conceptos', 'IVA otros conceptos') }}
                        {{ Form::text('iva_otros_conceptos', $model->iva_otros_conceptos, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="checkbox checkbox-success">
                        <input type="checkbox" id="imprimir_logo" class="check-box-value" {{ $model->imprimir_logo ? "checked" : "" }} value="1">
                        {{ Form::label('imprimir_logo', 'Imprimir logo') }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="checkbox checkbox-success">
                        <input type="checkbox" id="imprimir_datos_empresa" class="check-box-value" {{ $model->imprimir_datos_empresa ? "checked" : "" }} value="1">
                        {{ Form::label('imprimir_datos_empresa', 'Imprimir datos empresa') }}
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('intereses.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{ Form::hidden('imprimir_logo', $model->imprimir_logo) }}
    {{ Form::hidden('imprimir_datos_empresa', $model->imprimir_datos_empresa) }}
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\InteresesRequest', '#frmEdit') !!}

    <script>
        $(function()
        {

            $('.check-box-value').click(function(){
                var vName = $(this).attr('id');
                var vChecked = $(this).prop('checked') ? 1 : 0;

                $('[name="' + vName + '"]').val(vChecked);
            })

            $("#frmEdit").submit(function(){
                var vSubmit = $(this).valid();

                if (vSubmit) {
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading();
                }
                return vSubmit;
            })
        })
    </script>
@endsection