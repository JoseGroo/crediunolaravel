@extends('layouts.layout')




@section('content')
    {{ Form::open([ 'route' => ['divisas.compra_venta_post' ], 'method' => 'POST', 'id' => 'frmCrear' ]) }}

    <input type="hidden" id="dolar_venta" value="{{ $sucursal->dolar_venta }}">
    <input type="hidden" id="dolar_compra" value="{{ $sucursal->dolar_compra }}">

    <input type="hidden" id="dolar_moneda_venta" value="{{ $sucursal->dolar_moneda_venta }}">
    <input type="hidden" id="dolar_moneda_compra" value="{{ $sucursal->dolar_moneda_compra }}">

    <input type="hidden" id="euro_venta" value="{{ $sucursal->euro_venta }}">
    <input type="hidden" id="euro_compra" value="{{ $sucursal->euro_compra }}">

    <input type="hidden" id="iva_divisa" value="{{ $sucursal->iva_divisa }}">

    <div class="form-row">
        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Dolar</h5>
                    <hr>
                    <p class="card-text text-monospace">Venta: @money_format($sucursal->dolar_venta)</p>
                    <p class="card-text text-monospace">Compra: @money_format($sucursal->dolar_compra)</p>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Dolar Moneda</h5>
                    <hr>
                    <p class="card-text text-monospace">Venta: @money_format($sucursal->dolar_moneda_venta)</p>
                    <p class="card-text text-monospace">Compra: @money_format($sucursal->dolar_moneda_compra)</p>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Euro</h5>
                    <hr>
                    <p class="card-text text-monospace">Venta: @money_format($sucursal->euro_venta)</p>
                    <p class="card-text text-monospace">Compra: @money_format($sucursal->euro_compra)</p>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">IVA</h5>
                    <hr>
                    <p class="card-text text-monospace" style="font-size: 45px;">{{ number_format($sucursal->iva_divisa, 2) }}%</p>
                </div>
            </div>
        </div>
    </div>

        <div class="card mt-5">
            <div class="card-header">
                <h5 class="m-0">
                    Compra venta de divisa
                </h5>
            </div>
            <div class="card-body">


                <div class="form-row">

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('divisa_id', __('validation.attributes.divisa_id')) }}
                            {{ Form::select('divisa_id', $divisas, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion', 'autofocus' => true ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('tipo', __('validation.attributes.movimiento')) }}
                            {{ Form::select('tipo', $tipo_compra_venta_divisa, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('cantidad', __('validation.attributes.cantidad')) }}
                            {{ Form::text('cantidad', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12 compra-divisa" style="display: none;">
                        <div class="form-group">
                            {{ Form::label('paga_con', __('validation.attributes.paga_con')) }}
                            {{ Form::text('paga_con', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12 compra-divisa" style="display: none;">
                        <div class="checkbox checkbox-success" style="line-height: 1;">
                            <input type="checkbox" id="convertir_peso_divisa" class="check-box-value">
                            {{ Form::label('convertir_peso_divisa', 'Convertir de pesos a divisa') }}
                        </div>
                        <div class="form-group" style="display: none;" id="dConvertirPesoDivisa">
                            {{ Form::text('tbPesoDivisa', null, [ 'class' => 'form-control just-decimal', 'id' => 'tbPesoDivisa' ]) }}
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-4 compra-divisa" style="display: none;">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">IVA</h5>
                                <hr>
                                <p class="card-text text-monospace" style="font-size: 40px;">$<span id="sIVA" class="just-decimal">@money_format(0)</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total</h5>
                                <hr>
                                <p class="card-text text-monospace" style="font-size: 40px;">$<span id="sTotal" class="just-decimal">@money_format(0)</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 compra-divisa" style="display: none;">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Cambio</h5>
                                <hr>
                                <p class="card-text text-monospace" style="font-size: 40px;">$<span id="sCambio" class="just-decimal">@money_format(0)</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row mt-5">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <button type="button" id="btnAbrirModal" class="btn btn-sm btn-primary">Realizar movimiento</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}


<div class="modal inmodal" id="modalConfirmarMovimiento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInUp">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Verificar los datos del movimiento para procesarlo.</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Divisa</h5>
                                <hr>
                                <p class="card-text text-monospace" id="pDivisaConfirmation"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Cantidad</h5>
                                <hr>
                                <p class="card-text text-monospace" id="pCantidadConfirmation"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Importe</h5>
                                <hr>
                                <p class="card-text text-monospace" id="pImporteConfirmation"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="offset-4 col-md-4 compra-divisa">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">IVA</h5>
                                <hr>
                                <p class="card-text text-monospace" id="pIVAConfirmation"></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarMovimiento">Confirmar movimiento</button>
            </div>

        </div>
    </div>
</div>

@if($compra_venta_divisas)
    {{ Form::open([ 'route' => ['divisas.download_pdf' ], 'method' => 'POST', 'id' => 'frmGeneratePdf' ]) }}
    <input type="hidden" name="compra_venta_divisa_id" value="{{ $compra_venta_divisas->compra_venta_divisa_id }}">
    <input type="hidden" name="pago_con" value="{{ $compra_venta_divisas->pago_con }}">
    <input type="hidden" name="cambio" value="{{ $compra_venta_divisas->cambio }}">
    {{ Form::close() }}
@endif
@endsection

@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\CompraVentaDivisaRequest', '#frmCrear') !!}

    <script>
        var vCompraDivisa = 0;
        var vVentaDivisa = 0;
        var CalculateTotal = function(){
            var vTipo = $('#tipo').val();

            $('#sCambio, #sTotal, #sIVA').text('0.00');

            if(vTipo.length > 0)
            {
                var vCantidad = $('#cantidad').val();
                vCantidad = vCantidad.length > 0 ? parseFloat($('#cantidad').val()) : 0;

                //COMPRA
                if(vTipo == 1)
                {
                    $('.compra-divisa').hide();
                    var vTotal = vCantidad * vCompraDivisa;

                    $('#sTotal').text(NumberFormat(parseFloat(vTotal).toFixed(2)));
                }
                //VENTA
                else{
                    $('.compra-divisa').show();
                    var vTotal = vCantidad * vVentaDivisa;

                    var vIVA = parseFloat($('#iva_divisa').val());
                    var vImporteIVA = 0;
                    if(vIVA > 0)
                    {
                        vImporteIVA = (vTotal * vIVA)/100;
                        vTotal += vImporteIVA;
                    }
                    $('#sIVA').text(NumberFormat(parseFloat(vImporteIVA).toFixed(2)));


                    $('#sTotal').text(NumberFormat(parseFloat(vTotal).toFixed(2)));
                    var vPagaCon = $('#paga_con').val();
                    vPagaCon = vPagaCon.length > 0 ? parseFloat(vPagaCon) : vTotal;
                    var vCambio = vPagaCon - vTotal;
                    $('#sCambio').text(NumberFormat(parseFloat(vCambio).toFixed(2)));
                }

            }
        }


        $(function()
        {
            @if($compra_venta_divisas)
            Swal.fire({
                title: '¿Desea imprimir el ticket?',
                text: 'Se guardo correctamente su compra/venta de divisa.',
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

            $('#tbPesoDivisa').keyup(function(){

                if($('#divisa_id').val().length > 0)
                {
                    var vCantidadPesosDivisa = $(this).val();
                    var vIVA = parseFloat($('#iva_divisa').val());
                    var vImporteIVA = (vVentaDivisa * vIVA)/100;
                    var vValorDivisa = vImporteIVA + vVentaDivisa;
                    var vCantidadDivisas = vCantidadPesosDivisa / vValorDivisa;
                    $("#cantidad").val(parseInt(vCantidadDivisas).toFixed(2));
                    $('#paga_con').val(vCantidadPesosDivisa);
                    CalculateTotal();
                }
            })

            $('#convertir_peso_divisa').click(function(){
                var vChecked = $(this).prop('checked');
                $('#dConvertirPesoDivisa').hide();
                if(vChecked)
                {
                    $('#dConvertirPesoDivisa').show();
                }
            })

            $('#divisa_id').change(function(){
                var vId = $(this).val();
                vCompraDivisa  = 0;
                vVentaDivisa  = 0;

                switch (vId)
                {
                    case '1':
                        vCompraDivisa = $('#dolar_compra').val();
                        vVentaDivisa = $('#dolar_venta').val();
                        break;
                    case '2':
                        vCompraDivisa = $('#dolar_moneda_compra').val();
                        vVentaDivisa = $('#dolar_moneda_venta').val();
                        break;
                    case '3':
                        vCompraDivisa = $('#euro_compra').val();
                        vVentaDivisa = $('#euro_venta').val();
                        break;
                }
                CalculateTotal();
            })

            $('#tipo').change(CalculateTotal);

            $('#cantidad, #paga_con').keyup(function(){
                CalculateTotal();
            })

            $('#btnAbrirModal').click(function (){
                if(!$("#frmCrear").valid())
                    return;

                var vDivisa = $("#divisa_id option:selected").text();
                var vMovimiento = $("#tipo option:selected").text();

                $('#pDivisaConfirmation').text(vDivisa + '(' + vMovimiento + ')');
                var vCantidad = $('#cantidad').val();
                vCantidad = vCantidad.length > 0 ? NumberFormat(parseFloat(vCantidad).toFixed(2)) : '0.00';
                $('#pCantidadConfirmation').text(vCantidad);
                $('#pImporteConfirmation').text('$' + $('#sTotal').text());
                $('#pIVAConfirmation').text('$' + $('#sIVA').text());

                $('#modalConfirmarMovimiento').modal('show');
            })

            $('#btnConfirmarMovimiento').click(function(){
                $("#frmCrear").submit();
            })

            $("#frmCrear").submit(function(){
                var vSubmit = $(this).valid();

                if (vSubmit) {
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading();
                }
                return vSubmit;
            })


        })
    </script>
@endsection
