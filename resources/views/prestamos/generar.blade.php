@extends('layouts.layout')

@section("title", "Generar prestamo")


@section('content')

    {{ Form::open([ 'route' => ['prestamos.generar_post' ], 'method' => 'POST', 'id' => 'frmCrear', 'enctype' => 'multipart/form-data' ]) }}

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
                            {{ Form::label('capital', __('validation.attributes.capital')) }}
                            {{ Form::text('capital', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('interes_id', __('validation.attributes.interes_id')) }}
                            {{ Form::select('interes_id', $intereses, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
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
                            {{ Form::select('dia_descanso', $dias_semana, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-12">
                        <button type="button" data-toggle="modal" data-target="#modalAval" class="btn btn-success btn-lg"><i class="mdi mdi-face"></i> Agregar aval</button>
                        <button type="button" data-toggle="modal" data-target="#modalGarantia" class="btn btn-success btn-lg"><i class="mdi mdi-car-estate"></i> Agregar garantia</button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <a href="{{ route('clientes.details', $cliente->cliente_id) }}" class="btn btn-sm btn-secondary">Regresar</a>
                            <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::hidden('cobrar_dias_festivos') }}
    {{ Form::hidden('aplico_taza_preferencial') }}
    {{ Form::hidden('garantia_existente') }}
    {{ Form::hidden('cliente_id', $cliente->cliente_id) }}
    {{ Form::hidden('aval_id', null,[ 'id' => 'aval_id' ]) }}

    <div class="modal fade" id="modalGarantia" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar garantia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <div class="checkbox checkbox-success">
                                    <input type="checkbox" id="garantia_existente" class="check-box-value" value="1">
                                    {{ Form::label('garantia_existente', __('validation.attributes.garantia_existente')) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12 div-garantia-existente" style="display: none;">
                            <div class="form-group">

                                <select class="form-control" id="garantia_id" name="garantia_id">
                                    <option value="">Seleccionar opción</option>
                                    @foreach($garantias as $item)
                                        <option data-descripcion="{{ $item->descripcion }}" value="{{ $item->garantia_id }}">{{ \App\Enums\tipos_garantia::getDescription($item->tipo) }} - {{ $item->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12 div-garantia-existente" style="display: none;">
                            <div class="form-group">
                                <p id="pDescripcionGarantia"></p>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12 div-nueva-garantia">
                            <div class="alert alert-info" role="alert">
                                Para poder agregar la garantia debe de capturar todos los campos.
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12 div-nueva-garantia">
                            <div class="form-group">
                                {{ Form::label('tipo_garantia', __('validation.attributes.tipo_garantia')) }}
                                {{ Form::select('tipo', $tipos_garantia, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12 div-nueva-garantia">
                            <div class="form-group">
                                {{ Form::label('descripcion', __('validation.attributes.descripcion')) }}
                                {{ Form::textarea('descripcion', null, [ 'class' => 'form-control', 'rows' => 3 ]) }}
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-12 div-nueva-garantia">
                            <div class="form-group">
                                {{ Form::label('valor', __('validation.attributes.valor')) }}
                                {{ Form::text('valor', null, [ 'class' => 'form-control just-decimal' ]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>


    {{ Form::close() }}
    <div class="modal fade" id="modalAval">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar aval</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-8 col-sm-12 col-12 ">
                            <div class="form-group">
                                {{ Form::label('aval', __('validation.attributes.buscar_aval')) }}
                                {{ Form::text('aval_search', null, [ 'class' => 'form-control', 'id' => 'aval_search' ]) }}
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-12 ">
                            <label>&nbsp;</label>
                            <div class="form-group">
                                <button type="button" id="btnSearchAval" class="btn btn-sm btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="alert alert-info" role="alert">
                                La busqueda le muestra 10 resultados, favor de indicar el nombre lo mas completo posible
                            </div>
                        </div>
                        <div class="col-md-12" id="divInfoAvales"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

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
            </div>
        </div>
    </div>
@endsection


@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\PrestamoRequest', '#frmCrear') !!}

    <script>
        var Confirmed = false;

        $(document).on('click', '.seleccionar-aval', function(){
            var vAvalId = $(this).data('id');
            $('#aval_id').val(vAvalId);
            $('#modalAval').modal('hide');
            $('.seleccionar-aval').text('Seleccionar').addClass('btn-primary').removeClass('btn-success');
            $(this).text('Seleccionado').removeClass('btn-primary').addClass('btn-success');
        })
        $(function()
        {
            $('#periodo').change(function () {
                var vDisabledDiaPago = $(this).val() != 2;
                var vDisabledDescanso = $(this).val() != 1 && $(this).val() != 2;
                $('#dia_pago').prop('disabled', vDisabledDiaPago);
                $('#dia_descanso').prop('disabled', vDisabledDescanso);

            })

            $('#garantia_existente').click(function(){
                var vChecked = $(this).prop('checked');

                if(vChecked)
                {
                    $('.div-nueva-garantia').hide();
                    $('.div-garantia-existente').show();
                }else{
                    $('.div-garantia-existente').hide();
                    $('.div-nueva-garantia').show();
                }
            })

            $('#garantia_id').change(function(){
                var vDescripcion = $("#garantia_id option:selected").data('descripcion');
                $('#pDescripcionGarantia').text(vDescripcion == undefined ? "" : vDescripcion);
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

            $('#btnSearchAval').click(function(){
                var vAval = $('#aval_search').val();
                var vAvalSeleccionado = $('#aval_id').val();
                $.ajax({
                    url: "{{route('prestamos.get_list_avales_by_name')}}",
                    dataType: "html",
                    type: "GET",
                    data: {
                        nombre: vAval,
                        aval_id: vAvalSeleccionado
                    },
                    cache: false,
                    success: function (data) {
                        $('#divInfoAvales').html(data);
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })

            $("#frmCrear").submit(function(){
                var vSubmit = $(this).valid();

                if(vSubmit && !Confirmed)
                {
                    $('#lCapital').text($('#capital').val());
                    $('#lTipo').text($('#interes_id option:selected').text());
                    $('#lPlazo').text($('#plazo').val());

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