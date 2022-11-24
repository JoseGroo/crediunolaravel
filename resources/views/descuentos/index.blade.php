@extends('layouts.layout')

@section("title", "Listado de descuentos")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('descuentos.create') }}">Crear nuevo descuento</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['descuentos.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label class="font-normal">Rango de fechas</label>
                            <div class="input-daterange input-group">
                                <input type="text" class="form-control datepicker" readonly name="fecha_inicio" data-placeholder="Desde">
                                <span class="input-group-addon" style="padding-top: 12px;">a</span>
                                <input type="text" class="form-control datepicker" readonly name="fecha_fin" data-placeholder="Hasta">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Estatus</label>
                            {{ Form::select('estatus', $estatus_descuentos, $estatus, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue" type="submit">Buscar</button>
                        <a href="#" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("descuentos._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vDescuentoId = 0;

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vDescuentoId = $(this).data("descuento-id");

            Swal.fire({
                title: '¿Desea continuar?',
                text: 'El registro quedara eliminado.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#809fff',
                cancelButtonColor: '#d33',
                confirmButtonText: "Si, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                    ShowLoading("Eliminando registro...");
                    $.ajax({
                        url: "{{route('descuentos.cancel')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            descuento_id: vDescuentoId,
                            _token: "{{ csrf_token() }}"
                        },
                        cache: false,
                        success: function (data) {
                            if(data.Saved)
                                $("#frmIndex").submit();

                            Swal.fire('Notificación', data.Message, data.Saved ? 'success' : 'error');

                        },
                        error: function (error) {
                            console.log("error");
                            console.log(error.responseText);
                        }
                    });
                }
            })
        })
    </script>
@endsection
