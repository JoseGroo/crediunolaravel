@extends('layouts.layout')

@section("title", "Crear nuevo usuario")


@section('content')

    @include('general._errors')

    {{ Form::open([ 'route' => ['users.create_post' ], 'method' => 'POST', 'id' => 'frmCrear', 'enctype' => 'multipart/form-data' ]) }}

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
                            {{ Form::text('nombre', null, [ 'class' => 'form-control', 'autofocus' => true ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('apellido_paterno', 'Apellido paterno') }}
                            {{ Form::text('apellido_paterno', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('apellido_materno', 'Apellido materno') }}
                            {{ Form::text('apellido_materno', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('rol_id', 'Rol') }}
                            {{ Form::select('rol_id', $roles, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('usuario', 'Usuario') }}
                            {{ Form::text('usuario', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('password', 'Contraseña') }}
                            {{ Form::password('password', [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('password_confirmation', 'Confirmar contraseña') }}
                            {{ Form::password('password_confirmation', [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('sucursal_id', 'Sucursal') }}
                            {{ Form::select('sucursal_id', $sucursales, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group">
                            {{ Form::label('direccion', 'Dirección') }}
                            {{ Form::textarea('direccion', null, [ 'class' => 'form-control', 'rows' => 4 ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('telefono', 'Teléfono') }}
                            {{ Form::text('telefono', null, [ 'class' => 'form-control phone-mask' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('seguro_social', 'Seguro social') }}
                            {{ Form::text('seguro_social', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('foto', 'Foto') }}
                            {{ Form::file('foto', [ 'class' => 'form-control preview-image validate-image', 'preview-img-id' => 'imgPreviewFotoPerfil', 'accept' => 'image/*' ]) }}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('foto', 'Foto de perfil') }}
                        </div>

                        <img id="imgPreviewFotoPerfil" src="">
                    </div>



                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
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

    {!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#frmCrear') !!}
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