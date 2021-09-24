{{ Form::hidden('informacion_contacto_id', $model->informacion_contacto_id ?? 0, [ 'id' => 'informacion_contacto_id' ] ) }}


<div class="row">

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('telefono_fijo', __('validation.attributes.telefono_fijo')) }}
            {{ Form::text('telefono_fijo', $model->telefono_fijo ?? '', [ 'class' => 'form-control phone-mask' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('telefono_movil', __('validation.attributes.telefono_movil')) }}
            {{ Form::text('telefono_movil', $model->telefono_movil ?? '', [ 'class' => 'form-control phone-mask' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('correo_electronico', __('validation.attributes.correo_electronico')) }}
            {{ Form::text('correo_electronico', $model->correo_electronico ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('telefono_alternativo_1', __('validation.attributes.telefono_alternativo_1')) }}
            {{ Form::text('telefono_alternativo_1', $model->telefono_alternativo_1 ?? '', [ 'class' => 'form-control phone-mask' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('nombre_alternativo_1', __('validation.attributes.nombre_alternativo_1')) }}
            {{ Form::text('nombre_alternativo_1', $model->nombre_alternativo_1 ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('parentesco_alternativo_1', __('validation.attributes.parentesco_alternativo_1')) }}
            {{ Form::text('parentesco_alternativo_1', $model->parentesco_alternativo_1 ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('telefono_alternativo_2', __('validation.attributes.telefono_alternativo_2')) }}
            {{ Form::text('telefono_alternativo_2', $model->telefono_alternativo_2 ?? '', [ 'class' => 'form-control phone-mask' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('nombre_alternativo_2', __('validation.attributes.nombre_alternativo_2')) }}
            {{ Form::text('nombre_alternativo_2', $model->nombre_alternativo_2 ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('parentesco_alternativo_2', __('validation.attributes.parentesco_alternativo_2')) }}
            {{ Form::text('parentesco_alternativo_2', $model->parentesco_alternativo_2 ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-12">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-sm btn-primary">@lang('dictionary.save')</button>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\InformacionContactoRequest', '#frmInformacionContacto') !!}
