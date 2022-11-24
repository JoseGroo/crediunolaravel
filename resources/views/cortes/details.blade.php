@extends('layouts.layout')

@section('content')
<style>
    .card-text.text-monospace{
        font-size: 13px;
    }

    .checkbox input[type="checkbox"], .radio input[type="radio"]{
        left:-7px;
        top:4px;
    }
</style>
    <div class="card">
        <div class="card-header">
            <h5 class="m-0">
                Detalles del corte
            </h5>
        </div>
        <div class="card-body">

            <ul class="nav nav-tabs" id="tabCorte" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#tab-resumen" role="tab" aria-controls="tab-resumen" aria-selected="true">Resumen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-movimientos" role="tab" aria-controls="tab-movimientos" aria-selected="true">Movimientos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-traspasos-retiros" role="tab" aria-controls="tab-traspasos-retiros" aria-selected="true">Traspasos y retiros</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#tab-traspasos-entre-cajas" role="tab" aria-controls="tab-traspasos-entre-cajas" aria-selected="true">Traspasos entre cajas</a>
                </li>

            </ul>

            <div class="tab-content">
                <div id="tab-resumen" class="tab-pane active">
                    @include('cortes._resumen', [ 'model' => $model])
                </div>
                <div id="tab-movimientos" class="tab-pane"></div>
                <div id="tab-traspasos-retiros" class="tab-pane"></div>
                <div id="tab-traspasos-entre-cajas" class="tab-pane"></div>
            </div>

        </div>

    </div>

@endsection



@section("scripts")
    @parent

    <script src="{{ asset('js/jquery/jquery.form.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    <script>
        $(document).on('click', '#btnImprimirCorte', function(){
            MyToast("Notificación", "En construcciòn", "info");
        })
        $(document).on('click', '#btnCerrarCorte', function(){
            Swal.fire({
                title: '¿Desea continuar?',
                text: 'El corte quedara cerrado.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Cerrando corte...");

                    $.ajax({
                        url: "{{route('cortes.cerrar')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            corte_id: '{{ $model->corte_id }}'
                        },
                        cache: false,
                        success: function (data) {

                            var vClass = data.Saved ? "success" : "error";

                            if(data.Saved)
                            {
                                $('#tab-resumen').html(data.Html);
                            }
                            MyToast("Notificación", data.Message, vClass);
                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            })
        })

        $(document).on('click', '#btnCancelar', function(){

            var vIds = $(".item-reprint-cancel:checked")
                .map(function() {
                    return $(this).data('id');
                }).get();

            if(vIds.length <= 0)
            {
                MyToast('Notificación', 'Seleccione al menos un movimiento para cancelar', 'warning');
                return;
            }

            Swal.fire({
                title: '¿Desea continuar?',
                text: 'Los movimientos seleccionados quedaran cancelados.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Cancelando movimientos...");

                    $.ajax({
                        url: "{{route('cortes.cancelar_movimientos')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            ids: vIds,
                            _token: "{{ csrf_token() }}",
                            corte_id: '{{ $model->corte_id }}',
                        },
                        cache: false,
                        success: function (data) {

                            var vClass = data.Saved ? "success" : "error";

                            if(data.Saved)
                            {
                                $('#tab-movimientos').html(data.Html);
                            }
                            MyToast("Notificación", data.Message, vClass);
                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            })
        })

        $(document).on('click', '#btnReimprimir', function(){
            MyToast("Notificación", "En construcciòn", "info");
        })



        $(document).on('click', '#tabCorte [data-toggle="tab"]', function(){
            var vTab = $(this).attr('href').substring(1);

            ShowLoading('Cargando información...');
            $.ajax({
                url: "{{route('cortes.get_tab_details')}}",
                dataType: "html",
                type: "GET",
                data: {
                    corte_id: '{{ $model->corte_id }}',
                    tab: vTab
                },
                cache: false,
                success: function (data) {
                    $('#' + vTab).html(data);
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });
        })

    </script>
@endsection
