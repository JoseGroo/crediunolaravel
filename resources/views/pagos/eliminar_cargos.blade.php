@extends('layouts.layout')

@section("title", "Eliminar cargos")


@section('content')
    {{ Form::open([ 'route' => ['pagos.eliminar_cargos' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Buscar cliente</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            {{ Form::label('cliente', __('validation.attributes.cliente')) }}
                            {{ Form::text('cliente', null, [ 'class' => 'form-control' ]) }}
                            {{ Form::hidden('cliente_id', null, ['id' => 'cliente_id', 'class' => 'validate-input']) }}
                            {{ Form::hidden('cliente_hidden', null, ['id' => 'cliente_hidden']) }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue filtrar" type="submit">Buscar</button>
                        <a href="{{ route('pagos.eliminar_cargos') }}" class="btn btn-sm btn-white">Limpiar</a>
                    </div>
                </div>
            </div>


            <div class="card-body">
                @include("pagos._eliminar_cargos")
            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        $(document).on('click', '.delete-cargo', function (){
            var $Tr = $(this).parents('tr');
            var vId = $(this).data('cargo-id');

            Swal.fire({
                title: '¿Desea continuar?',
                text: 'El cargo quedara eliminado.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Eliminando cargo...");
                    $.ajax({
                        url: '{{ route('pagos.eliminar_cargos_post') }}',
                        type: "POST",
                        dataType: "json",
                        data: {
                            cargo_id: vId,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            console.log(data);
                            if(data.Saved)
                                $Tr.remove();

                            Swal.fire('Notificación', data.Message, data.Saved ? 'success' : 'error');
                        },
                        error: function (error) {
                            alert(error.responseText);
                        }
                    });
                }
            })



        })
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
                }
            });
        })
    </script>
@endsection
