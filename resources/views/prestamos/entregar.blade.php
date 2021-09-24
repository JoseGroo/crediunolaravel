@extends('layouts.layout')

@section("title", "Entregar prestamo")


@section('content')



    {{ Form::open([ 'route' => ['prestamos.entregar_post' ], 'method' => 'POST', 'id' => 'frmEntregar', 'enctype' => 'multipart/form-data' ]) }}

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
                    <div class="col-12">
                        <h5>Prestamos por entregar:</h5>
                    </div>
                </div>
                <div class="form-row">

                    @foreach($prestamos as $item)
                        <div>
                            <button type="button" data-id="{{ $item->prestamo_id }}" class="btn btn-secondary prestamo m-1">#{{ $item->folio }}</button>
                        </div>
                    @endforeach
                </div>

                <div id="divInfoPrestamo"></div>

                <div class="form-row mt-5">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="form-group text-right">
                            <a href="{{ route('clientes.details', $cliente->cliente_id) }}" class="btn btn-sm btn-secondary">Regresar</a>
                            <button type="button" class="btn btn-sm btn-primary action-prestamo" data-action="1">Entregar prestamo</button>
                            <button type="button" class="btn btn-sm btn-danger action-prestamo" data-action="2">Cancelar prestamo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::hidden('cliente_id', $cliente->cliente_id) }}
    <input type="hidden" id="action" name="action" value="">
    {{ Form::close() }}

@endsection


@section("scripts")
    @parent

    <script>
        var Action = null;
        var PrestamoId = null;


        $(function()
        {
            @if(session()->has('capital_entregada'))
            Swal.fire('Notificación', 'Se guardo correctamente la información. Entregar al cliente: @money_format(session("capital_entregada"))', 'success');
            @endif

            $('.action-prestamo').click(function(event){
                event.preventDefault();

                if(PrestamoId == null)
                {
                    MyToast('Notificación', "Debe de seleccionar un prestamo antes de intentar entregarlo o cancelarlo.", 'warning');
                    return false;
                }

                Action = $(this).data('action');
                var vMessage = Action == 1 ? "Esta a punto de entregar el prestamo con folio " + $('#hFolio').val() + " y una cantidad de " + $('#hCapital').val()
                            : "Esta a punto de cancelar el prestamo con folio " + $('#hFolio').val();

                Swal.fire({
                    title: '¿Desea continuar?',
                    text: vMessage,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#809fff',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Si, continuar",
                    cancelButtonText: "No, cancelar"
                }).then((result) => {
                    if (result.value) {
                        $("#frmEntregar").submit();
                    }
                });
            })
            $('.prestamo').click(function () {
                PrestamoId = $(this).data('id');
                $.ajax({
                    url: "{{route('prestamos.get_prestamo_by_id')}}",
                    dataType: "html",
                    type: "GET",
                    data: {
                        prestamo_id: PrestamoId
                    },
                    cache: false,
                    success: function (data) {
                        $('#divInfoPrestamo').html(data);
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            });


            $("#frmEntregar").submit(function(){
                if(Action == null || PrestamoId == null)
                    return false;

                var vSubmit = $(this).valid();


                if (vSubmit) {
                    $('#action').val(Action);
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading();
                }
                return vSubmit;
            })
        })

    </script>
@endsection