{{ Form::hidden('conyuge_aval_id', $model->conyuge_aval_id ?? 0, [ 'id' => 'conyuge_aval_id' ] ) }}


<div class="row">

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('nombre', 'Nombre') }}
            {{ Form::text('nombre', $model->nombre ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('apellido_paterno', 'Apellido paterno') }}
            {{ Form::text('apellido_paterno', $model->apellido_paterno ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('apellido_materno', 'Apellido materno') }}
            {{ Form::text('apellido_materno', $model->apellido_materno ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('fecha_nacimiento', 'Fecha de nacimiento') }}
            {{ Form::text('fecha_nacimiento', $model->fecha_nacimiento ?? '', [ 'class' => 'form-control datepicker', 'readonly' => true ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('telefono_movil', __('validation.attributes.telefono_movil')) }}
            {{ Form::text('telefono_movil', $model->telefono_movil ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('lugar_trabajo', __('validation.attributes.lugar_trabajo')) }}
            {{ Form::text('lugar_trabajo', $model->lugar_trabajo ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('puesto', __('validation.attributes.puesto')) }}
            {{ Form::text('puesto', $model->puesto ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>

    <div class="col-md-4 col-sm-6 col-12">
        <div class="form-group">
            {{ Form::label('jefe', __('validation.attributes.jefe')) }}
            {{ Form::text('jefe', $model->jefe ?? '', [ 'class' => 'form-control' ]) }}
        </div>
    </div>


    <div class="col-md-12 col-sm-12 col-12">
        <div class="form-group text-right">
            <button type="submit" class="btn btn-sm btn-primary">@lang('dictionary.save')</button>
        </div>
    </div>
</div>

{!! JsValidator::formRequest('App\Http\Requests\ConyugeAvalRequest', '#frmConyuge') !!}
