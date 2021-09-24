{{ Form::hidden('informacion_laboral_id', $model->informacion_laboral_id ?? 0, [ 'id' => 'informacion_laboral_id' ] ) }}


<div class="row">

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('empresa', __('validation.attributes.empresa')) }}
            {{ Form::text('empresa', $model->empresa ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('pais', __('validation.attributes.pais')) }}
            {{ Form::text('pais', $model->pais ?? 'Mexico', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('estado_id',  __('validation.attributes.estado_id')) }}
            {{ Form::select('estado_id', $estados, $model->estado_id ?? auth()->user()->sucursal->estado_id, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('localidad', __('validation.attributes.localidad')) }}
            {{ Form::text('localidad', $model->localidad ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('colonia', __('validation.attributes.colonia')) }}
            {{ Form::text('colonia', $model->colonia ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('numero_exterior', __('validation.attributes.numero_exterior')) }}
            {{ Form::text('numero_exterior', $model->numero_exterior ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('calle', __('validation.attributes.calle')) }}
            {{ Form::text('calle', $model->calle ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('codigo_postal', __('validation.attributes.codigo_postal')) }}
            {{ Form::text('codigo_postal', $model->codigo_postal ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('jefe_inmediato', __('validation.attributes.jefe_inmediato')) }}
            {{ Form::text('jefe_inmediato', $model->jefe_inmediato ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('telefono', __('validation.attributes.telefono_jefe')) }}
            {{ Form::text('telefono', $model->telefono ?? '', [ 'class' => 'form-control phone-mask' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('departamento', __('validation.attributes.departamento')) }}
            {{ Form::text('departamento', $model->departamento ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('antiguedad', __('validation.attributes.antiguedad')) }}
            <div class="row">
                <div class="col-md-8" style="padding-right: 0;">
                    {{ Form::text('antiguedad', $model->antiguedad ?? '', [ 'class' => 'form-control just-number' ]) }}
                </div>
                <div class="col-md-4" style="padding-left: 0;">
                    {{ Form::select('unidad_antiguedad', $unidades_tiempo, $model->unidad_antiguedad ?? '', ['class' => 'form-control' ]) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-12">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-sm btn-primary">@lang('dictionary.save')</button>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\InformacionLaboralAvalRequest', '#frmInformacionLaboral') !!}
