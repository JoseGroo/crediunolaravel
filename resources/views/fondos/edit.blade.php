@extends('layouts.layout')

@section("title", "Editar fondo")


@section('content')

    @include('general._errors')

    {{ Form::open([ 'route' => ['fondos.edit_post' ], 'method' => 'POST', 'id' => 'frmEdit' ]) }}

    {{ Form::hidden('fondo_id', $model->fondo_id) }}

    <div class="card">
        <div class="card-header">
            <h5 class="m-0">
                Datos
            </h5>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('fondo', 'Fondo') }}
                        {{ Form::text('fondo', $model->fondo, [ 'class' => 'form-control', 'autofocus' => true ]) }}
                    </div>
                </div>
                @php
                    $tipo_fondo = old('tipo') ?? $model->tipo;
                    $display_datos_banco = $tipo_fondo == 1 ? '' : 'display: none;';
                @endphp
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('tipo', 'Tipo') }}

                        <div class="row col-md-12">
                            @foreach($tipos_fondos as $item)
                                <div class="radio col-md-6">
                                    <input type="radio" {{ $tipo_fondo == $item->value ? 'checked' : '' }} id="tipo{{ $item->value }}" value="{{ $item->value }}" name="tipo">
                                    <label for="tipo{{ $item->value }}"> {{ $item->description }} </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>



                <div class="col-md-4 col-sm-6 col-12 datos-banco" style="{{ $display_datos_banco }}">
                    <div class="form-group">
                        {{ Form::label('banco', 'Banco') }}
                        {{ Form::text('banco', $model->banco, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12 datos-banco" style="{{ $display_datos_banco }}">
                    <div class="form-group">
                        {{ Form::label('cuenta', 'Cuenta') }}
                        {{ Form::text('cuenta', $model->cuenta, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 col-12 datos-banco" style="{{ $display_datos_banco }}">
                    <div class="form-group">
                        {{ Form::label('ultimo_cheque_girado', 'Ultimo cheque girado') }}
                        {{ Form::text('ultimo_cheque_girado', $model->ultimo_cheque_girado, [ 'class' => 'form-control' ]) }}
                    </div>
                </div>

            </div>

            <div class="form-row">
                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('importe_pesos', 'Importe en pesos') }}
                        {{ Form::text('importe_pesos', $model->importe_pesos, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('importe_dolares', 'Importe en dolares') }}
                        {{ Form::text('importe_dolares', $model->importe_dolares, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('importe_dolares_moneda', 'Importe en dolares moneda') }}
                        {{ Form::text('importe_dolares_moneda', $model->importe_dolares_moneda, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>

                <div class="col-md-2 col-sm-6 col-12">
                    <div class="form-group">
                        {{ Form::label('importe_euros', 'Importe en euros') }}
                        {{ Form::text('importe_euros', $model->importe_euros, [ 'class' => 'form-control just-decimal' ]) }}
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="form-group text-right">
                        <a href="{{ route('fondos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
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

    {!! JsValidator::formRequest('App\Http\Requests\FondosRequest', '#frmEdit') !!}

    <script>
        $(function()
        {
            $("#frmEdit").submit(function(){
                var vSubmit = $(this).valid();

                if (vSubmit) {
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading();
                }
                return vSubmit;
            })

            $('[name="tipo"]').click(function(){
                var vTipo = $('[name="tipo"]:checked').val();

                if(vTipo == 1)
                {
                    $('.datos-banco').show();

                }else{
                    $('.datos-banco').hide();
                }
            })
        })
    </script>
@endsection