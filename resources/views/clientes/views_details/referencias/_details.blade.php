<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('nombre', __('validation.attributes.nombre')) }}
        <div class="form-control-plaintext">{{ $model->nombre }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('apellido_paterno', __('validation.attributes.apellido_paterno')) }}
        <div class="form-control-plaintext">{{ $model->apellido_paterno }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('apellido_materno', __('validation.attributes.apellido_materno')) }}
        <div class="form-control-plaintext">{{ $model->apellido_materno }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('telefono_fijo', __('validation.attributes.telefono_fijo')) }}
        <div class="form-control-plaintext">{{ $model->telefono_fijo }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('telefono_movil', __('validation.attributes.telefono_movil')) }}
        <div class="form-control-plaintext">{{ $model->telefono_movil }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('telefono_oficina', __('validation.attributes.telefono_oficina')) }}
        <div class="form-control-plaintext">{{ $model->telefono_oficina }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('correo_electronico', __('validation.attributes.correo_electronico')) }}
        <div class="form-control-plaintext">{{ $model->correo_electronico }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('calle', __('validation.attributes.calle')) }}
        <div class="form-control-plaintext">{{ $model->calle }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('numero_exterior', __('validation.attributes.numero_exterior')) }}
        <div class="form-control-plaintext">{{ $model->numero_exterior }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('colonia', __('validation.attributes.colonia')) }}
        <div class="form-control-plaintext">{{ $model->colonia }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('tiempo_conocerlo', __('validation.attributes.tiempo_conocerlo')) }}
        <div class="form-control-plaintext">{{ $model->tiempo_conocerlo_completo }}</div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('relacion', __('validation.attributes.relacion')) }}
        <div class="form-control-plaintext">{{ $model->relacion }}</div>
    </div>
</div>