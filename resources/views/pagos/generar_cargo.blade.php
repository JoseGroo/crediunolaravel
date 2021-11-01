@extends('layouts.layout')

@section("title", "Generar cargo manual")


@section('content')

    {{ Form::open([ 'route' => ['pagos.generar_manual' ], 'method' => 'POST', 'id' => 'frmCrear' ]) }}

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
                            {{ Form::label('cliente', __('validation.attributes.cliente')) }}
                            {{ Form::text('cliente', null, [ 'class' => 'form-control' ]) }}
                            {{ Form::hidden('cliente_id', null, ['id' => 'cliente_id', 'class' => 'validate-input']) }}
                            {{ Form::hidden('cliente_hidden', null, ['id' => 'cliente_hidden']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('prestamo_id', __('validation.attributes.prestamo')) }}
                            {{ Form::select('prestamo_id', [], null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('adeudo_id', __('validation.attributes.recibo')) }}
                            {{ Form::select('adeudo_id', [], null, ['class' => 'form-control', 'placeholder' => 'Seleccionar opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="form-group">
                            {{ Form::label('importe', __('validation.attributes.importe')) }}
                            {{ Form::text('importe', null, [ 'class' => 'form-control' ]) }}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            {{ Form::label('comentario', __('validation.attributes.comentario')) }}
                            {{ Form::textarea('comentario', null, [ 'class' => 'form-control', 'rows' => 4 ]) }}
                        </div>
                    </div>

                </div>

                <div class="form-row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
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

    {!! JsValidator::formRequest('App\Http\Requests\CargosManualesRequest', '#frmCrear') !!}

    <script>

        $.validator.setDefaults({
            ignore: ":hidden:not(.validate-input)",

        });

        var GetPrestamos = function(){
            var vClienteId = $('#cliente_id').val();
            $.ajax({
                url: "{{ route('prestamos.get_prestamos_by_cliente_id') }}",
                dataType: "json",
                type: "GET",
                data: {
                    cliente_id: vClienteId
                },
                cache: false,
                success: function (data) {
                    console.log(data);
                    $('#prestamo_id').empty().append('<option>Seleccionar opcion</option>');

                    if(data != null)
                    {
                        for(var item of data)
                        {
                            $('#prestamo_id').append('<option value="' + item.prestamo_id + '">' + item.folio + '</option>');
                        }
                    }
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });
        }

        $(function()
        {
            $("#cliente").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: '{{ route('clientes.autocomplete_cliente') }}',
                        type: "POST",
                        dataType: "json",
                        data: {
                            cliente: $("#cliente").val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            response($.map(data, function (item) {
                                return item;
                            }));
                        },
                        error: function (error) {
                            alert(error.responseText);
                        }
                    });
                },
                change: function(){
                    if($('#cliente_hidden').val() != $('#cliente').val())
                        $('#cliente_hidden, #cliente_id').val('');
                },
                select: function (event, ui) {
                    $('#cliente_id').val(ui.item.cliente_id);
                    $('#cliente_hidden').val(ui.item.label);
                    GetPrestamos();
                }
            });



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
