@extends('layouts.layout')

@section("title", "Crear nuevo aval")


@section('content')

    {{ Form::open([ 'route' => ['avales.create_post' ], 'method' => 'POST', 'id' => 'frmCrear', 'enctype' => 'multipart/form-data' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="m-0">
                    Datos de sucursal
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            @php
                                $sucursal_id = old('sucursal_id') ?? auth()->user()->sucursal_id;
                            @endphp
                            {{ Form::label('sucursal_id', 'Sucursal') }}
                            {{ Form::select('sucursal_id', $sucursales, $sucursal_id, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion', 'autofocus' => true ]) }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-header border-top">
                <h5 class="m-0">
                    Datos personales
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('foto', 'Foto') }}
                            {{ Form::file('foto', [ 'class' => 'form-control preview-image validate-image', 'preview-img-id' => 'imgPreviewFotoPerfil', 'accept' => 'image/*' ]) }}
                        </div>
                    </div>

                    <div class="col-md-8 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('foto', 'Foto de perfil') }}
                        </div>

                        <img id="imgPreviewFotoPerfil" src="">
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('nombre', 'Nombre') }}
                            {{ Form::text('nombre', null, [ 'class' => 'form-control' ]) }}
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
                            {{ Form::label('fecha_nacimiento', 'Fecha de nacimiento') }}
                            {{ Form::text('fecha_nacimiento', null, [ 'class' => 'form-control datepicker', 'readonly' => true ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('sexo', 'Sexo') }}
                            {{ Form::select('sexo', $sexos, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('estado_civil', 'Estado civil') }}
                            {{ Form::select('estado_civil', $estados_civiles, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('ocupacion', 'Ocupación') }}
                            {{ Form::text('ocupacion', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                </div>
            </div>

            @php
                $show_datos_conyuge = old('estado_civil') == 2 || old('estado_civil') == 4 ? '' : 'display:none;';
            @endphp
            <div class="card-header border-top div-datos-conyuge" style="{{ $show_datos_conyuge }}">
                <h5 class="m-0">
                    Datos conyuge
                </h5>
            </div>
            <div class="card-body div-datos-conyuge" style="{{ $show_datos_conyuge }}">
                <div class="form-row">

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('nombre_conyuge', 'Nombre') }}
                            {{ Form::text('nombre_conyuge', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('apellido_paterno_conyuge', 'Apellido paterno') }}
                            {{ Form::text('apellido_paterno_conyuge', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('apellido_materno_conyuge', 'Apellido materno') }}
                            {{ Form::text('apellido_materno_conyuge', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('fecha_nacimiento_conyuge', 'Fecha de nacimiento') }}
                            {{ Form::text('fecha_nacimiento_conyuge', null, [ 'class' => 'form-control datepicker', 'readonly' => true ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('telefono_movil_conyuge', __('validation.attributes.telefono_movil')) }}
                            {{ Form::text('telefono_movil_conyuge', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('lugar_trabajo_conyuge', __('validation.attributes.lugar_trabajo')) }}
                            {{ Form::text('lugar_trabajo_conyuge', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('puesto_conyuge', __('validation.attributes.puesto')) }}
                            {{ Form::text('puesto_conyuge', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('jefe_conyuge', __('validation.attributes.jefe')) }}
                            {{ Form::text('jefe_conyuge', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-header border-top">
                <h5 class="m-0">
                    Dirección
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">


                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('pais', 'País') }}
                            {{ Form::text('pais', old('pais') ?? 'Mexico', [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('estado_id', 'Estado') }}
                            {{ Form::select('estado_id', $estados, old('estado_id') ?? auth()->user()->sucursal->estado_id, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('localidad', 'Localidad') }}
                            {{ Form::text('localidad', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('calle', 'Calle') }}
                            {{ Form::text('calle', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('numero_exterior', 'Número exterior') }}
                            {{ Form::text('numero_exterior', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('numero_interior', 'Número interior') }}
                            {{ Form::text('numero_interior', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('colonia', 'Colonia') }}
                            {{ Form::text('colonia', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('entre_calles', 'Entre calles') }}
                            {{ Form::text('entre_calles', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('senas_particulares', 'Señas particulares') }}
                            {{ Form::text('senas_particulares', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('codigo_postal', 'Código postal') }}
                            {{ Form::text('codigo_postal', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('tiempo_residencia', 'Tiempo de residencia') }}
                            <div class="row">
                                <div class="col-md-8" style="padding-right: 0;">
                                    {{ Form::text('tiempo_residencia', null, [ 'class' => 'form-control just-number' ]) }}
                                </div>
                                <div class="col-md-4" style="padding-left: 0;">
                                    {{ Form::select('unidad_tiempo_residencia', $unidades_tiempo, null, ['class' => 'form-control' ]) }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <a href="{{ route('home') }}" class="btn btn-sm btn-secondary">Cancelar</a>
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

    {!! JsValidator::formRequest('App\Http\Requests\AvalesRequest', '#frmCrear') !!}

    <script>

        $(function()
        {
            $('#estado_civil').change(function () {
                var vEstadoCivil = $(this).val();
                if(vEstadoCivil == 2 || vEstadoCivil == 4)
                    $('.div-datos-conyuge').slideDown();
                else
                    $('.div-datos-conyuge').slideUp();
            })
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