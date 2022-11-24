@extends('layouts.layout')

@section("title", "Listado de sucursales")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('sucursales.create') }}">Crear nueva sucursal</a>
@endsection

@section('content')

    {{ Form::open([ 'route' => ['sucursales.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Sucursal</label>
                            <input type="text" autocomplete="off" name="sSucursal" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Estado</label>
                            {{ Form::select('estado_id', $estados, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Ciudad</label>
                            {{ Form::select('ciudad_id', $ciudades, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue" type="submit">Buscar</button>
                        <a href="{{ route('sucursales.index') }}" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("sucursales._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vSucursalId = 0;

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vSucursalId = $(this).attr("sucursal-id");

            Swal.fire({
                title: '¿Desea continuar?',
                text: 'El registro se va a eliminar.',
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
                        url: "{{route('sucursales.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            sucursal_id: vSucursalId,
                            _token: "{{ csrf_token() }}"
                        },
                        cache: false,
                        success: function (data) {
                            if(data.Saved)
                            {
                                $("#frmIndex").submit();
                            }
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
