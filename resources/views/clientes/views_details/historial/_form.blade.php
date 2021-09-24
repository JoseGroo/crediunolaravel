{{ Form::hidden('historial_cliente_id', $model->historial_cliente_id ?? 0, [ 'id' => 'historial_cliente_id' ] ) }}


<div class="row">
    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            <div class="checkbox checkbox-success">
                <input type="checkbox" id="tiene_adeudo" class="check-box-value" {{ ($model->tiene_adeudo ?? false) ? "checked" : "" }} value="1">
                {{ Form::label('tiene_adeudo', __('validation.attributes.tiene_adeudo')) }}
            </div>
            {{ Form::hidden('tiene_adeudo', $model->tiene_adeudo ?? 0) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('acreedor', __('validation.attributes.acreedor')) }}
            {{ Form::text('acreedor', $model->acreedor ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('telefono', __('validation.attributes.telefono')) }}
            {{ Form::text('telefono', $model->telefono ?? '', [ 'class' => 'form-control phone-mask' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('adeudo', __('validation.attributes.adeudo')) }}
            {{ Form::text('adeudo', $model->adeudo ?? '', [ 'class' => 'form-control just-decimal' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            <div class="checkbox checkbox-success">
                <input type="checkbox" id="esta_al_corriente" class="check-box-value" {{ ($model->esta_al_corriente ?? false) ? "checked" : "" }} value="1">
                {{ Form::label('esta_al_corriente', __('validation.attributes.esta_al_corriente')) }}
            </div>
            {{ Form::hidden('esta_al_corriente', $model->esta_al_corriente ?? 0) }}
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-12">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-sm btn-primary">@lang('dictionary.save')</button>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\HistorialClienteRequest', '#frmHistorial') !!}