@php
    $foto_perfil =  Storage::exists($model->foto) ? $model->foto : "public/user_profile/default.png";
@endphp
<div class="row">
    {{ Form::hidden('aval_id', $model->aval_id) }}
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
                            $sucursal_id = $model->sucursal_id ?? auth()->user()->sucursal_id;
                        @endphp
                        {{ Form::label('sucursal_id', __('validation.attributes.sucursal')) }}
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
                        {{ Form::label('foto', __('validation.attributes.foto')) }}
                        {{ Form::file('foto', [ 'class' => 'form-control preview-image validate-image', 'preview-img-id' => 'imgPreviewFotoPerfil', 'accept' => 'image/*' ]) }}
                    </div>
                </div>

                <div class="col-md-8 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('foto', 'Foto de perfil') }}
                    </div>

                    <img id="imgPreviewFotoPerfil" style="height: 150px;" src="{{ Storage::url($foto_perfil) }}">
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('nombre', 'Nombre') }}
                        {{ Form::text('nombre', $model->nombre, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('apellido_paterno', 'Apellido paterno') }}
                        {{ Form::text('apellido_paterno', $model->apellido_paterno, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('apellido_materno', 'Apellido materno') }}
                        {{ Form::text('apellido_materno', $model->apellido_materno, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('fecha_nacimiento', 'Fecha de nacimiento') }}
                        {{ Form::text('fecha_nacimiento', $model->fecha_nacimiento, [ 'class' => 'form-control datepicker', 'readonly' => true ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('sexo', 'Sexo') }}
                        {{ Form::select('sexo', $sexos, $model->sexo, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('estado_civil', 'Estado civil') }}
                        {{ Form::select('estado_civil', $estados_civiles, $model->estado_civil, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('ocupacion', 'Ocupación') }}
                        {{ Form::text('ocupacion', $model->ocupacion, [ 'class' => 'form-control' ]) }}
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
                        {{ Form::text('pais', $model->pais ?? 'Mexico', [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('estado_id', 'Estado') }}
                        {{ Form::select('estado_id', $estados, $model->estado_id ?? auth()->user()->sucursal->estado_id, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('localidad', 'Localidad') }}
                        {{ Form::text('localidad', $model->localidad, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('calle', 'Calle') }}
                        {{ Form::text('calle', $model->calle, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('numero_exterior', 'Número exterior') }}
                        {{ Form::text('numero_exterior', $model->numero_exterior, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('numero_interior', 'Número interior') }}
                        {{ Form::text('numero_interior', $model->numero_interior, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('colonia', 'Colonia') }}
                        {{ Form::text('colonia', $model->colonia, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('entre_calles', 'Entre calles') }}
                        {{ Form::text('entre_calles', $model->entre_calles, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('senas_particulares', 'Señas particulares') }}
                        {{ Form::text('senas_particulares', $model->senas_particulares, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('codigo_postal', 'Código postal') }}
                        {{ Form::text('codigo_postal', $model->codigo_postal, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('tiempo_residencia', 'Tiempo de residencia') }}
                        <div class="row">
                            <div class="col-md-8" style="padding-right: 0;">
                                {{ Form::text('tiempo_residencia', $model->tiempo_residencia, [ 'class' => 'form-control just-number' ]) }}
                            </div>
                            <div class="col-md-4" style="padding-left: 0;">
                                {{ Form::select('unidad_tiempo_residencia', $unidades_tiempo, $model->unidad_tiempo_residencia, ['class' => 'form-control' ]) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>