@extends('layouts.layout')

@section("title", "Simulador de prestamo")


@section('content')

    {{ Form::open([ 'route' => ['prestamos.simulate_post' ], 'method' => 'POST', 'id' => 'frmSimulate' ]) }}

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
                            {{ Form::label('capital', __('validation.attributes.capital')) }}
                            {{ Form::text('capital', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('interes_id', __('validation.attributes.interes_id')) }}
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
                            <button type="button" id="btnSimulate" class="btn btn-sm btn-primary">Simular</button>
                        </div>
                    </div>
                </div>

                <div id="divAmortizacion"></div>
            </div>
        </div>
    {{ Form::hidden('cobrar_dias_festivos') }}
    {{ Form::hidden('aplico_taza_preferencial') }}


    {{ Form::close() }}

@endsection


@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\SimulacionPrestamoRequest', '#frmSimulate') !!}

    <script>

        var downloadPdf = false;
        $(function()
        {
            $('#periodo').change(function () {
                var vDisabledDiaPago = $(this).val() != 2;
                var vDisabledDescanso = $(this).val() != 1 && $(this).val() != 2;
                $('#dia_pago').prop('disabled', vDisabledDiaPago);
                $('#dia_descanso').prop('disabled', vDisabledDescanso);

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

            $("#frmSimulate").submit(function(){
                var vSubmit = $(this).valid();
                var $form = this;
                if(vSubmit){
                    if(!downloadPdf)
                    {
                        ShowLoading();

                        $.ajax({
                            url: "{{route('prestamos.get_adeudos_simulador')}}",
                            dataType: "html",
                            type: "POST",
                            data: $($form).serialize(),
                            cache: false,
                            success: function (data) {
                                $('#divAmortizacion').html(data);
                            },
                            error: function (error) {
                                console.log("error");
                                console.log(error.responseText);
                            }
                        });
                    }else{
                        return true;
                    }
                }

                return false;
            })


        })

        $(document).on('click', '#btnDownloadSimulation', function(){
            $('#frmSimulate').prop('action', '{{ route('prestamos.simulacion_pdf') }}');
            downloadPdf = true;
            $('#frmSimulate').submit();
        })

        $(document).on('click', '#btnSimulate', function(){
            $('#frmSimulate').prop('action', '{{ route('prestamos.simulate_post') }}');
            downloadPdf = false;
            $('#frmSimulate').submit();
        })
    </script>
@endsection
