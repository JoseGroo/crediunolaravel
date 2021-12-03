@extends('layouts.layout')

@section("title", "Pagos")

@section('content')
    {{ Form::open([ 'route' => ['clientes.pago_post' ], 'method' => 'POST', 'id' => 'frmPagos' ]) }}
    {{ Form::hidden('cliente_id', $model->cliente_id) }}

    <div class="wrapper wrapper-content">
        <div class="row animated fadeInRight">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h5 class="m-0">
                                    Realizar pagos de cliente
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-12 col-12">
                                @php
                                    $foto_perfil =  Storage::exists($model->foto) ? $model->foto : "public/user_profile/default.png";
                                @endphp
                                <div class="ibox-content no-padding text-center">
                                    <img alt="image" style="width: 163px; height: 163px;" class="img-fluid img-circle" src="{{ Storage::url($foto_perfil) }}">
                                    <h5 class="text-center">
                                        <strong>#{{ $model->cliente_id }} - {{ $model->nombre }} {{ $model->apellido_paterno }} {{ $model->apellido_materno }}</strong>
                                        <span id="spanEstatus">({{ \App\Enums\estatus_cliente::getDescription($model->estatus) }})</span>
                                    </h5>
                                </div>
                            </div>

                            <div class="col-md-9 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="m-0">
                                            Datos del prestamo
                                        </h6>
                                    </div>
                                    <div class="card-body" id="divDetallesPrestamo">
                                        @include('prestamos._detalles_prestamo')
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label class="d-block">&nbsp;</label>
                                <a href="{{ route('clientes.details', $model->cliente_id) }}" class="btn btn-sm btn-secondary">Regresar</a>
                            </div>

                            <div class="col-md-2">
                                <div class="checkbox checkbox-success">
                                    <input type="checkbox" name="ocultar_cargos" id="ocultar_cargos" class="check-box-value" value="0" placeholder="">
                                    <label for="ocultar_cargos">Ocultar cargos</label>
                                </div>
                            </div>

                            @if($prestamos->count() > 1)
                                <div class="col-md-2">
                                    {{ Form::label('prestamo_id', 'Prestamo:', ['class' => 'font-weight-bold']) }}
                                    {{ Form::select('prestamo_id', $prestamos->pluck('folio', 'prestamo_id'), 0, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                                </div>
                            @endif

                            <div class="col-md-2">
                                {{ Form::label('acreditar_otra_cantidad', 'Acreditar otra cantidad:', ['class' => 'font-weight-bold']) }}
                                <div class="input-group">
                                    {{ Form::text('acreditar_otra_cantidad', null, [ 'class' => 'form-control just-decimal' ]) }}
                                    <span class="input-group-append">
                                        <button type="button" id="btnOtraCantidad" class="btn-xs btn-primary "><i class="mdi mdi-format-list-checks" style="font-size: 24px;"></i></button>
                                    </span>
                                </div>

                            </div>

                            <div class="col-md-2">
                                <label class="d-block">&nbsp;</label>
                                <button type="button" id="btnPagar" class="btn btn-sm btn-success">Pagar</button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="m-0">Recibos pendientes</h5>
                            </div>
                        </div>
                        <div id="divPagos">
                            @include('prestamos._pagos')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal inmodal" id="modalRealizarPago" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Realizar pago</h4>
                </div>

                <div class="modal-body">


                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                {{ Form::label('forma_pago', 'Forma de pago') }}
                                {{ Form::select('forma_pago', $formas_pago, 1, ['class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12">
                            <div class="form-group">
                                {{ Form::label('total_pagar', 'Total a pagar') }}
                                {{ Form::text('total_pagar', null, [ 'class' => 'form-control just-decimal', 'readonly' => true ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divNumeroTarjeta" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('numero_tarjeta', 'Número de tarjeta') }}
                                {{ Form::text('numero_tarjeta', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divCuentaReceptora" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('cuenta_receptora', 'Cuenta receptora') }}
                                {{ Form::text('cuenta_receptora', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divBanco" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('banco', 'Banco') }}
                                {{ Form::text('banco', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divCuentahabiente" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('cuentahabiente', 'Cuentahabiente') }}
                                {{ Form::text('cuentahabiente', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divTipoTarjeta" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('tipo_tarjeta', 'Tipo de tarjeta') }}
                                {{ Form::select('tipo_tarjeta', $tipos_tarjetas, null, ['class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divNombrePropietario" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('nombre_propietario', 'Nombre del propietario') }}
                                {{ Form::text('nombre_propietario', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divNumeroCheque" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('numero_cheque', 'Número de cheque') }}
                                {{ Form::text('numero_cheque', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12 datos-pago" id="divNumeroCuenta" style="display: none;">
                            <div class="form-group">
                                {{ Form::label('numero_cuenta', 'Número de cuenta') }}
                                {{ Form::text('numero_cuenta', null, [ 'class' => 'form-control' ]) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                        </div>
                    </div>

                    <div class="row" id="divPagaConCambio">
                        <div class="col-md-6 col-sm-12 col-12" id="divPagaCon">
                            <div class="form-group">
                                {{ Form::label('paga_con', 'Paga con') }}
                                {{ Form::text('paga_con', null, [ 'class' => 'form-control just-decimal' ]) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-12 col-12" id="divCambio">
                            <div class="form-group">
                                {{ Form::label('cambio', 'Cambio') }}
                                {{ Form::text('cambio', null, [ 'class' => 'form-control just-decimal', 'readonly' => true ]) }}
                            </div>
                        </div>
                    </div>




                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnRealizarPago">Realizar pago</button>
                </div>

            </div>
        </div>
    </div>

    <div id="divPagosHidden"></div>
    {{ Form::close() }}

    @if($pagos)
        {{ Form::open([ 'route' => ['clientes.download_pdf_pagos' ], 'method' => 'POST', 'id' => 'frmGeneratePdf' ]) }}
        @foreach($pagos as $item)
            <input type="hidden" name="pagos_ids[]" value="{{ $item->pago_id }}">
        @endforeach
        <input type="hidden" name="paga_con" value="{{ $paga_con }}">
        <input type="hidden" name="cambio" value="{{ $cambio }}">
        {{ Form::hidden('cliente_id', $model->cliente_id) }}
        {{ Form::close() }}
    @endif
@endsection



@section("scripts")
    @parent


    <script>

        $(function()
        {
            @if($pagos)
            Swal.fire({
                title: '¿Desea imprimir el ticket?',
                text: 'Se guardo correctamente el pago.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    $('#frmGeneratePdf').submit();
                }
            })
            @endif

            $('#ocultar_cargos').click(function(){
                if($(this).prop('checked'))
                {
                    $('.tr-cargo').hide();
                }else{
                    $('.tr-cargo').show();
                }
            })

            $('#prestamo_id').change(function () {
                ShowLoading('Cargando recibos...');
                var vId = $(this).val();
                $.ajax({
                    url: "{{ route('prestamos.get_pagos_by_prestamo_id') }}",
                    dataType: "json",
                    type: "GET",
                    data: {
                        'id': vId,
                        'cliente_id': {{ $model->cliente_id }}
                    },
                    cache: false,
                    success: function (data) {
                        $('#divPagos').html(data.HtmlPagos);
                        $('#divDetallesPrestamo').html(data.HtmlDetallesPrestamo);
                        HideLoading();
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })

            $('#btnOtraCantidad').click(function () {
                var vImporteAcreditar = $('#acreditar_otra_cantidad').val();
                if(vImporteAcreditar.length > 0)
                {
                    ShowLoading('Cargando información...');
                    var vId = $(this).val();
                    $.ajax({
                        url: "{{ route('prestamos.get_pagos_acreditar_cantidad') }}",
                        dataType: "html",
                        type: "GET",
                        data: {
                            'id': vId,
                            'importe_acreditar': vImporteAcreditar,
                            'cliente_id': {{ $model->cliente_id }}
                        },
                        cache: false,
                        success: function (data) {
                            $('#divPagos').html(data);
                            HideLoading();
                            SetTotalPagar();
                            $('#modalRealizarPago').modal('show');
                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            })

            $('#btnPagar').click(function (event) {
                if($('.adeudo-checkbox:checked').length <= 0)
                {
                    MyToast('Notificación', 'Debe de seleccionar al menos un recibo para poder realizar el pago.', 'warning');
                    return;
                }
                SetTotalPagar();
                $('#modalRealizarPago').modal('show');
            })

            $('#forma_pago').change(function(){
                var vFormaPago = $(this).val();
                $('.datos-pago').hide();
                $('#divPagaConCambio').show();
                switch (vFormaPago) {
                    case '2':
                        $('#divBanco, #divNumeroCheque, #divNumeroCuenta').show();
                        break;
                    case '3':
                        $('#divBanco, #divNumeroTarjeta, #divTipoTarjeta, #divNombrePropietario').show();
                        break;
                    case '4':
                        $('#divPagaConCambio').hide();
                        break;
                    case '5':
                        $('#divBanco, #divCuentaReceptora, #divCuentahabiente').show();
                        break;
                    case '6':
                        $('#divBanco, #divCuentaReceptora, #divCuentahabiente').show();
                        break;
                }
            })

            $('#paga_con').focus(function () {
                this.select();
            }).keyup(function () {
                var vPagaConValue = $(this).val();
                var vPagaCon = vPagaConValue.length > 0 ? parseFloat(vPagaConValue) : 0;
                var vTotalPago = parseFloat($('#total_pagar').val());
                var vCambio = vPagaCon - vTotalPago;
                $('#cambio').val(vCambio.toFixed(2));
            }).focusout(function () {
                if($(this).val().length == 0)
                {
                    $(this).val($('#total_pagar').val());
                    $(this).val($('#total_pagar').val());
                }
            })

            $('#btnRealizarPago').click(function () {
                $('#divPagosHidden').html('');
                $('.adeudo-checkbox:checked').each(function (index, element) {
                    var vType = $(element).data('type');
                    var vId = $(element).val();
                    var vAbono = $(element).parents('tr').find('.abono-recibo').val();
                    var vInputs = '<input name="adeudos[' + index + '][adeudo_id]" type="hidden" value="' + vId + '" />';
                    vInputs += '<input name="adeudos[' + index + '][tipo]" type="hidden" value="' + vType + '" />';
                    vInputs += '<input name="adeudos[' + index + '][abono]" type="hidden" value="' + vAbono + '" />';

                    $('#divPagosHidden').append(vInputs);
                })
                ShowLoading('Realizando pago...');
                $('#frmPagos').submit();
            })
        })

        var SetTotalPagar = function(){
            var vTotalPago = 0;
            $('.adeudo-checkbox:checked').each(function (index, element) {
                var vTotalAdeudo = parseFloat($(element).parents('tr').find('.abono-recibo').val());
                vTotalPago += vTotalAdeudo;
            })
            $('#total_pagar, #paga_con').val(vTotalPago);
            $('#cambio').val('0');
        }

        $(document).on('click', '.adeudo-checkbox', function () {
            var $inputAbono = $(this).parents('tr').find('.abono-recibo');
            if($(this).prop('checked'))
            {
                $inputAbono.prop('disabled', false);
                var vImporteTotal = $(this).parents('tr').find('.importe-total').data('importe-total');
                $inputAbono.val(vImporteTotal);
            }else{
                $inputAbono.val("");
                $inputAbono.prop('disabled', true);
            }
        })

        $(document).on('click', '#select_all', function () {
            var vChecked = $(this).prop('checked');
            $('.adeudo-checkbox').prop('checked', vChecked);
            $('.adeudo-checkbox').parents('tr').find('.abono-recibo').prop('disabled', !vChecked);
            $('.adeudo-checkbox').each(function (index, element) {
                var vImporteTotal = vChecked ? $(element).parents('tr').find('.importe-total').data('importe-total') : "";
                $(element).parents('tr').find('.abono-recibo').val(vImporteTotal);
            })

        })

        $(document).on('change', '.abono-recibo', function () {
            var vPagoTotal = parseFloat($(this).parents('tr').find('.importe-total').data('importe-total'));


            var vPago = parseFloat($(this).val());

            if(vPago > vPagoTotal)
            {
                var vPagoTotalText = $(this).parents('tr').find('.importe-total').text();
                MyToast("Notificación", "El pago maximo que acepta este recibo es de: " + vPagoTotalText, "warning");
                $(this).val(vPagoTotal);
            }
        })
    </script>
@endsection
