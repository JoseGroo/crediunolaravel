@extends('layouts.layout')

@section("title", "Listado de días festivos")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('dias_festivos.create') }}">Crear nuevo día festivo</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['dias_festivos.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

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
                            <label>Razón</label>
                            <input type="text" autocomplete="off" name="sRazon" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-12">
                        <div class="checkbox checkbox-success" style="padding-top: 15px;">
                            <input type="checkbox" name="mostrar_dias_pasados" id="mostrar_dias_pasados" class="check-box-value" value="1">
                            {{ Form::label('mostrar_dias_pasados', 'Mostrar días pasados') }}
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

                @include("dias_festivos._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vDiaFestivoId = 0;

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vDiaFestivoId = $(this).data("dia-festivo-id");

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
                        url: "{{route('dias_festivos.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            dia_festivo_id: vDiaFestivoId,
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
