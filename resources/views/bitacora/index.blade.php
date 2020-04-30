@extends('layouts.layout')

@section("title", "Bitacora")

@section('content')
    {{ Form::open([ 'route' => ['bitacora.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" autocomplete="off" name="sUsuario" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label class="font-normal">Rango de fechas</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="form-control datepicker" readonly name="fecha_inicio" value="{{ $fecha_inicio }}" data-placeholder="Desde">
                                <span class="input-group-addon" style="padding-top: 12px;">a</span>
                                <input type="text" class="form-control datepicker" readonly name="fecha_fin" value="{{ $fecha_fin }}" data-placeholder="Hasta">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Catalago</label>
                            {{ Form::select('catalago_sistema', $catalagos_sistema, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue filtrar" type="submit">Buscar</button>
                        <a href="{{ route('bitacora.index') }}" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("bitacora._index")

            </div>
        </div>
    {{ Form::close() }}

    <div class="modal fade" tabindex="-1" role="dialog" id="modalDetalleBitacora">
        <div class="modal-dialog modal-bitacora" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de edición</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="dDetalleBitacora">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection



@section("scripts")
    @parent

    <script>
        $(document).on('click', '.detalle-bitacora', function (event) {
            event.preventDefault();
            var vBitacoraId = $(this).attr('bitacora-id');

            ShowLoading('Cargando detalles...');
            $.ajax({
                url: "{{route('bitacora.get_detalles_by_id')}}",
                dataType: "json",
                type: "GET",
                data: {
                    bitacora_id: vBitacoraId
                },
                cache: false,
                success: function (data) {
                    console.log(data);
                    $('#dDetalleBitacora').html(data);
                    $('#modalDetalleBitacora').modal('show');
                },
                error: function (error) {
                    console.log("error");
                    console.log(error.responseText);
                }
            });
        })
    </script>
@endsection