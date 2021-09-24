@extends('layouts.layout')

@section("title", "Editar medio publicitario")


@section('content')

    {{ Form::open([ 'route' => ['medios-publicitarios.edit_post' ], 'method' => 'POST', 'id' => 'frmEdit' ]) }}

    {{ Form::hidden('medio_publicitario_id', $model->medio_publicitario_id) }}

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
                        {{ Form::label('medio_publicitario', 'Medio publicitario') }}
                        {{ Form::text('medio_publicitario', $model->medio_publicitario, [ 'class' => 'form-control', 'autofocus' => true ]) }}
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('medios-publicitarios.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
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

    {!! JsValidator::formRequest('App\Http\Requests\MediosPublicitariosRequest', '#frmEdit') !!}

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