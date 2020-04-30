@extends('layouts.layout')

@section("title", "Listado de grupos")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('grupos-cliente.create') }}">Crear nuevo grupo</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['grupos-cliente.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Grupo</label>
                            <input type="text" autocomplete="off" name="sGrupo" class="form-control" />
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

                @include("grupos_cliente._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vGrupoId = 0;

        $(document).on('click', '.add-client', function(){
            event.preventDefault();

            vGrupoId = $(this).attr("grupo-id");
            Swal.fire("En construccion, Grupo Id: " + vGrupoId);
        })

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vGrupoId = $(this).attr("grupo-id");

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
                        url: "{{route('grupos-cliente.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            grupo_id: vGrupoId,
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