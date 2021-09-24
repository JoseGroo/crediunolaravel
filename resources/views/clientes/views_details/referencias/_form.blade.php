
{{ Form::hidden('referencia_cliente_id', $model->referencia_cliente_id ?? 0, [ 'id' => 'referencia_cliente_id' ] ) }}

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('nombre', __('validation.attributes.nombre')) }}
        {{ Form::text('nombre', $model->nombre ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('apellido_paterno', __('validation.attributes.apellido_paterno')) }}
        {{ Form::text('apellido_paterno', $model->apellido_paterno ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('apellido_materno', __('validation.attributes.apellido_materno')) }}
        {{ Form::text('apellido_materno', $model->apellido_materno ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('telefono_fijo', __('validation.attributes.telefono_fijo')) }}
        {{ Form::text('telefono_fijo', $model->telefono_fijo ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('telefono_movil', __('validation.attributes.telefono_movil')) }}
        {{ Form::text('telefono_movil', $model->telefono_movil ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('telefono_oficina', __('validation.attributes.telefono_oficina')) }}
        {{ Form::text('telefono_oficina', $model->telefono_oficina ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('correo_electronico', __('validation.attributes.correo_electronico')) }}
        {{ Form::text('correo_electronico', $model->correo_electronico ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('calle', __('validation.attributes.calle')) }}
        {{ Form::text('calle', $model->calle ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('numero_exterior', __('validation.attributes.numero_exterior')) }}
        {{ Form::text('numero_exterior', $model->numero_exterior ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('colonia', __('validation.attributes.colonia')) }}
        {{ Form::text('colonia', $model->colonia ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('tiempo_conocerlo', __('validation.attributes.tiempo_conocerlo')) }}
        <div class="row">
            <div class="col-md-8" style="padding-right: 0;">
                {{ Form::text('tiempo_conocerlo', $model->tiempo_conocerlo ?? '', [ 'class' => 'form-control just-number' ]) }}
            </div>
            <div class="col-md-4" style="padding-left: 0;">
                {{ Form::select('unidad_tiempo_conocerlo', $unidades_tiempo, $model->unidad_tiempo_conocerlo ?? '', ['class' => 'form-control' ]) }}
            </div>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('relacion', __('validation.attributes.relacion')) }}
        {{ Form::text('relacion', $model->relacion ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>