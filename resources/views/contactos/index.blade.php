@extends('layouts.layout')

@section("title", "Listado de contactos")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('contactos.create') }}">Crear nuevo contacto</a>
@endsection

@section('content')
    {{ Form::open([ 'route' => ['contactos.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" autocomplete="off" name="sNombre" class="form-control" />
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" autocomplete="off" name="sTelefono" class="form-control" />
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

                @include("contactos._index")

            </div>
        </div>
    {{ Form::close() }}

@endsection



@section("scripts")
    @parent

    <script>
        var vContactos = 0;

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vContactos = $(this).data("contacto-id");

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
                        url: "{{route('contactos.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            contacto_id: vContactos,
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
