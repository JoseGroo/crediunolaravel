@extends('layouts.layout')

@section("title", "Crear nuevo grupo")


@section('content')

    {{ Form::open([ 'route' => ['grupos-cliente.create_post' ], 'method' => 'POST', 'id' => 'frmCrear' ]) }}

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
                            {{ Form::text('grupo', null, [ 'class' => 'form-control', 'autofocus' => true ]) }}
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <a href="{{ route('grupos-cliente.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
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

    {!! JsValidator::formRequest('App\Http\Requests\GruposRequest', '#frmCrear') !!}

    <script>
        $(function()
        {
            $("#frmCrear").submit(function(){
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