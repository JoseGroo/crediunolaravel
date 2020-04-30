@extends('layouts.layout')

@section("title", "Listado de fondos")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('fondos.create') }}">Crear nuevo fondo</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['fondos.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Fondo</label>
                            <input type="text" autocomplete="off" name="sFondo" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Tipo de fondo</label>
                            {{ Form::select('tipo_fondo', $tipos_fondo, null, ['class' => 'form-control', 'placeholder' => 'Selecciona opcion']) }}
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue filtrar" type="submit">Buscar</button>
                        <a href="#" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("fondos._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vFondoId = 0;

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            var vAccion = $(this).text().trim();

            var vTextSwal = vAccion === 'Desactivar' ? 'El registro quedara desactivado' : 'El registro se reactivara';

            vFondoId = $(this).attr("fondo-id");

            Swal.fire({
                title: '¿Desea continuar?',
                text: vTextSwal,
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
                        url: "{{route('fondos.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            fondo_id: vFondoId,
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