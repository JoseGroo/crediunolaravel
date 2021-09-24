{{ Form::hidden('economia_id', $model->economia_id ?? 0, [ 'id' => 'economia_id' ] ) }}


<div class="row">

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('ingresos_propios', __('validation.attributes.ingresos_propios')) }}
            {{ Form::text('ingresos_propios', $model->ingresos_propios ?? '', [ 'class' => 'form-control just-decimal' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('ingresos_conyuge', __('validation.attributes.ingresos_conyuge')) }}
            {{ Form::text('ingresos_conyuge', $model->ingresos_conyuge ?? '', [ 'class' => 'form-control just-decimal' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('otros_ingresos', __('validation.attributes.otros_ingresos')) }}
            {{ Form::text('otros_ingresos', $model->otros_ingresos ?? '', [ 'class' => 'form-control just-decimal' ]) }}
        </div>
    </div>


    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('gastos_fijos', __('validation.attributes.gastos_fijos')) }}
            {{ Form::text('gastos_fijos', $model->gastos_fijos ?? '', [ 'class' => 'form-control just-decimal' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('gastos_eventuales', __('validation.attributes.gastos_eventuales')) }}
            {{ Form::text('gastos_eventuales', $model->gastos_eventuales ?? '', [ 'class' => 'form-control just-decimal' ]) }}
        </div>
    </div>


    <div class="col-md-12 col-sm-12 col-12">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-sm btn-primary">@lang('dictionary.save')</button>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\EconomiaRequest', '#frmEconomia') !!}
