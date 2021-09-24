@extends('layouts.layout')

@section("title", "Editar contacto")


@section('content')

    {{ Form::open([ 'route' => ['contactos.edit_post' ], 'method' => 'POST', 'id' => 'frmEdit' ]) }}

    {{ Form::hidden('contacto_id', $model->contacto_id) }}

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


                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('telefono', 'Teléfono') }}
                        {{ Form::text('telefono', $model->telefono, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('correo_electronico', 'Correo electrónico') }}
                        {{ Form::text('correo_electronico', $model->correo_electronico, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('direccion', 'Dirección') }}
                        {{ Form::textarea('direccion', $model->direccion, [ 'class' => 'form-control', 'rows' => 4 ]) }}
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('contactos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                        <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\ContactosRequest', '#frmEdit') !!}

    <script>

        $(function()
        {
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