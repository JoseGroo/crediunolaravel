@extends('layouts.layout')

@section('content')
    {{ Form::open([ 'route' => ['cobro_otros_conceptos.cobro_post' ], 'method' => 'POST', 'id' => 'frmCrear' ]) }}


        <div class="card mt-5">
            <div class="card-header">
                <h5 class="m-0">
                    Cobro otros conceptos
                </h5>
            </div>
            <div class="card-body">

                <div class="form-row">


                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('concepto', __('validation.attributes.concepto')) }}
                            {{ Form::text('concepto', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('importe', __('validation.attributes.importe')) }}
                            {{ Form::text('importe', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('paga_con', __('validation.attributes.paga_con')) }}
                            {{ Form::text('paga_con', null, [ 'class' => 'form-control just-decimal' ]) }}
                        </div>
                    </div>

                </div>

                <div class="form-row">

                    <div class="col-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Total</h5>
                                <hr>
                                <p class="card-text text-monospace" style="font-size: 40px;">$<span id="sTotal" class="just-decimal">@money_format(0)</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
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
                            <button type="submit" class="btn btn-sm btn-primary">Realizar movimiento</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}



@if($cobro_otro_concepto)
    {{ Form::open([ 'route' => ['cobro_otros_conceptos.download_pdf' ], 'method' => 'POST', 'id' => 'frmGeneratePdf' ]) }}
    <input type="hidden" name="cobro_otro_concepto_id" value="{{ $cobro_otro_concepto->cobro_otro_concepto_id }}">
    <input type="hidden" name="paga_con" value="{{ $cobro_otro_concepto->paga_con }}">
    <input type="hidden" name="cambio" value="{{ $cobro_otro_concepto->cambio }}">
    {{ Form::close() }}
@endif
@endsection

@section("scripts")
    @parent

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\CobroOtrosConceptosRequest', '#frmCrear') !!}

    <script>



        $(function()
        {
            @if($cobro_otro_concepto)
            $('#frmGeneratePdf').submit();
            @endif
                var CalcularDetalles = function(){
                    $('#sCambio, #sTotal').text('0.00');
                    var vImporte = $('#importe').val();
                    vImporte = vImporte.length > 0 ? parseFloat(vImporte) : 0;

                    var vPagaCon = $('#paga_con').val();
                    vPagaCon = vPagaCon.length > 0 && vPagaCon > 0 ? parseFloat(vPagaCon) : vImporte;

                    var vCambio = vPagaCon - vImporte;
                    $('#sCambio').text(NumberFormat(vCambio.toFixed(2)));

                    $('#sTotal').text(NumberFormat(vImporte.toFixed(2)));
                }


            $('#paga_con').keyup(function(){
                CalcularDetalles();
            })

            $('#importe').keyup(function(){
                CalcularDetalles();
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
