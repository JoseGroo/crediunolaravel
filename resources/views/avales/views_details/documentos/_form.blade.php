
{{ Form::hidden('documento_aval_id', $model->documento_aval_id ?? 0, [ 'id' => 'documento_aval_id' ] ) }}

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('tipo', 'Tipo') }}
        {{ Form::select('tipo', $tipos_documento, $model->tipo ?? 0, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('documento', 'Nombre del documento') }}
        {{ Form::text('documento', $model->documento ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('clave_identificacion', 'Clave de identificación') }}
        {{ Form::text('clave_identificacion', $model->clave_identificacion ?? '', [ 'class' => 'form-control' ]) }}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {{ Form::label('file', 'Archivo') }}
        {{ Form::file('file', [ 'class' => 'form-control' ]) }}
    </div>
</div>