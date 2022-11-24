@extends('layouts.layout')

@section("title", "Reestructurar prestamo")


@section('content')

    {{ Form::open([ 'route' => ['prestamos.reestructurar_post' ], 'method' => 'POST', 'id' => 'frmCrear', 'enctype' => 'multipart/form-data' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="m-0">
                    Datos
                </h5>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <h5 class="text-center">
                        <strong>Cliente: </strong>#{{ $cliente->cliente_id }} - {{ $cliente->full_name }}
                    </h5>
                </div>
                <div class="form-row">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('capital', __('validation.attributes.cantidad')) }}
                            {{ Form::text('capital', number_format($prestamo->total_capital, 2), [ 'class' => 'form-control', 'disabled' => true ]) }}
                            <input value="{{ $prestamo->total_capital }}" id="capital_value"  type="hidden"/>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('interes_id', __('validation.attributes.interes_id')) }}
                            {{--{{ Form::select('interes_id', $intereses, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}--}}
                            <select class="form-control" name="interes_id" id="interes_id">
                                <option>Seleccionar opcion</option>
                                @foreach($intereses as  $item)
                                    <option value="{{ $item->interes_id }}" data-all-info="{{ $item }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('periodo', __('validation.attributes.periodo')) }}
                            {{ Form::select('periodo', $periodos, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="checkbox checkbox-success">
                            <input type="checkbox" id="aplico_taza_preferencial" class="check-box-value" value="1">
                            {{ Form::label('aplico_taza_preferencial', __('validation.attributes.aplico_taza_preferencial')) }}
                        </div>

                        <div class="form-group">
                            {{ Form::text('taza_iva', null, [ 'class' => 'form-control just-decimal', 'disabled' => true, 'id' => 'taza_iva' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('plazo', __('validation.attributes.plazo')) }}
                            {{ Form::text('plazo', null, [ 'class' => 'form-control just-number' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dia_pago', __('validation.attributes.dia_pago')) }}
                            {{ Form::select('dia_pago', $dias_semana, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dia_pago_manual', __('validation.attributes.dia_pago_manual')) }}
                            {{ Form::text('dia_pago_manual', null, [ 'class' => 'form-control datepicker', 'readonly' => true ]) }}
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="checkbox checkbox-success">
                            <input type="checkbox" id="cobrar_dias_festivos" class="check-box-value" value="1">
                            {{ Form::label('cobrar_dias_festivos', __('validation.attributes.cobrar_dias_festivos')) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('dia_descanso', __('validation.attributes.dia_descanso')) }}
                            {{ Form::select('dia_descanso', $dias_semana, \App\Enums\dias_semana::Domingo, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-sm btn-primary">Generar reestructura</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::hidden('cobrar_dias_festivos') }}
    {{ Form::hidden('aplico_taza_preferencial') }}
    {{ Form::hidden('cliente_id', $cliente->cliente_id) }}
    {{ Form::hidden('prestamo_id', $prestamo->prestamo_id) }}

    {{ Form::close() }}

    <div hidden>
        <div id="divConfirmacionDatos">
            <h4>Confirmacion de datos</h4>
            <div class="form-row">
                <div class="col-md-12">
                    <label>@lang('validation.attributes.capital'):</label>
                    <label id="lCapital"></label>
                </div>

                <div class="col-md-12">
                    <label>@lang('validation.attributes.tipo'):</label>
                    <label id="lTipo"></label>
                </div>

                <div class="col-md-12">
                    <label>@lang('validation.attributes.plazo'):</label>
                    <label id="lPlazo"></label>
                </div>

                <div class="col-md-12" style="font-size: 25px;">
                    <label class="font-bold">Importe por pago:</label>
                    <label class="font-bold" id="lImportePorPago"></label>
                </div>
            </div>
        </div>
    </div>
@endsection


@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\ReestructuraPrestamoRequest', '#frmCrear') !!}

    <script>
        var Confirmed = false;
        var periodoMultiplicacion = 0;

        $(function()
        {
            $('#periodo').change(function () {
                var vDisabledDiaPago = $(this).val() != 2;
                var vDisabledDescanso = $(this).val() != 1 && $(this).val() != 2;
                $('#dia_pago').prop('disabled', vDisabledDiaPago);
                $('#dia_descanso').prop('disabled', vDisabledDescanso);
                switch (parseInt($(this).val())){
                    case 1:
                        periodoMultiplicacion = 1;
                        break;
                    case 2:
                        periodoMultiplicacion = 4;
                        break;
                    case 3:
                        periodoMultiplicacion = 2;
                        break;
                    case 4:
                        periodoMultiplicacion = 1;
                        break;
                }
            })

            $('#aplico_taza_preferencial').click(function () {
                var vChecked = $(this).prop('checked');
                if(vChecked)
                {
                    $('#taza_iva').prop('disabled', false);
                }else{
                    $('#taza_iva').prop('disabled', true);
                    $('#taza_iva').val('');
                }
            })
            var vLastInteresId = 0;
            $('#interes_id').change(function () {
                $("#periodo option[value='1']").attr('disabled',false);
                $("#periodo option[value='2']").attr('disabled',false);
                $("#periodo option[value='3']").attr('disabled',false);
                $("#periodo option[value='4']").attr('disabled',false);

                $('#plazo').attr('readonly', false);
                $('#plazo').val(vLastInteresId == 2 ? '' : $('#plazo').val());
                $('#periodo').val("");
                switch ($(this).val()) {
                    case "1":
                        $("#periodo option[value='1']").attr('disabled',true);
                        break;
                    case "2":
                        $("#periodo option[value='1']").attr('disabled',true);
                        $("#periodo option[value='2']").attr('disabled',true);
                        $("#periodo option[value='3']").attr('disabled',true);
                        $('#plazo').attr('readonly', true);
                        $('#plazo').val(1);
                        $('#periodo').val(4);
                        $('#periodo').trigger('change');
                        break;
                    case "4":
                        $("#periodo option[value='2']").attr('disabled',true);
                        $("#periodo option[value='3']").attr('disabled',true);
                        $("#periodo option[value='4']").attr('disabled',true);
                        $('#periodo').val(1);
                        $('#periodo').trigger('change');
                        break;
                }
                vLastInteresId = $(this).val();
            })


            $("#frmCrear").submit(function(){
                var vSubmit = $(this).valid();

                if(vSubmit && !Confirmed)
                {
                    $('#lCapital').text($('#capital').val());
                    $('#lTipo').text($('#interes_id option:selected').text());
                    $('#lPlazo').text($('#plazo').val());
                    var plazo = parseInt($("#plazo").val());
                    var cantidadPagos = parseInt(periodoMultiplicacion) * plazo;
                    var capital = parseFloat($("#capital_value").val());
                    var adeudoCapital = capital / cantidadPagos
                    var interesObj = $("#interes_id option:selected").data('all-info');

                    var tazaIva = $('#aplico_taza_preferencial').prop('checked') ? parseFloat($('#taza_iva').val()) : parseFloat(interesObj.interes_mensual);
                    var importeIntereses = (capital * tazaIva)/100

                    var importeIva = (importeIntereses *  parseFloat(interesObj.iva))/100;
                    var adeudoIva= plazo * importeIva;
                    var adeudoInteres = plazo * importeIntereses;

                    var interes = adeudoInteres/cantidadPagos;
                    var iva = adeudoIva/cantidadPagos;
                    var totalPago = adeudoCapital  + interes + iva;

                    $('#lImportePorPago').text('$' + NumberFormat(totalPago.toFixed(2)));
                    var divConfirmacion = $('#divConfirmacionDatos').clone();
                    Swal.fire({
                        title: '¿Desea continuar?',
                        html: divConfirmacion,//'El registro quedara eliminado.',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#809fff',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "Si, continuar",
                        cancelButtonText: "No, cancelar"
                    }).then((result) => {
                        if (result.value) {
                            Confirmed = true;
                            $("#frmCrear").submit();
                        }
                    });
                }

                if (vSubmit && Confirmed) {
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading();
                }
                return vSubmit && Confirmed;
            })
        })

    </script>
@endsection
