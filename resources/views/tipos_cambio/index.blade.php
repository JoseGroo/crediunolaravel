@extends('layouts.layout')

@section("title", "Listado de tipos de cambio")

@section('content')

    <div class="card">

            <div class="card-body">

                <div id="IndexList">

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                <tr>
                                    <th>Tipo de cambio</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $item->sucursal }}</td>
                                        <td>{{ $item->estado->estado }}</td>
                                        <td>{{ $item->ciudad->ciudad }}</td>
                                        <td>{{ $item->beneficiario }}</td>
                                        <td>
                                            <a href="{{ route('sucursales.edit', $item->sucursal_id ) }}" class="btn btn-sm btn-info">Editar</a>
                                            <a href="{{ route('sucursales.details', $item->sucursal_id) }}" class="btn btn-sm btn-white">Detalles</a>
                                            <button type="button" sucursal-id="{{ $item->sucursal_id }}" class="btn btn-sm btn-danger delete">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @include("layouts._pagination", ['model' => $model])
                </div>

            </div>
        </div>


@endsection



@section("scripts")
    @parent

    <script>
        var vUserId = 0;

        $(function(){
            $("#frmCambiarContrasena").validate({
                rules: {
                    password: "required",
                    confirmar_password: {
                        equalTo: "#password"
                    }
                },
                messages: {
                    confirmar_password: {
                        equalTo: 'La contraseña y su confirmación no coinciden.'
                    }
                },
                errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
                },
            });

            $('#frmCambiarContrasena').submit(function(event){
                event.preventDefault();
                var vForm = $(this);

                if(!vForm.valid())
                    return;

                $('#id').val(vUserId);
                ShowLoading("Cambiando contraseña...");
                $.ajax({
                    url: "{{route('users.cambiar_contrasena')}}",
                    dataType: "json",
                    type: "POST",
                    data: vForm.serialize(),
                    cache: false,
                    success: function (data) {
                        Swal.fire('Notificación', data.Message, data.Saved ? 'success' : 'error');
                        if(data.Saved)
                            $("#modalCambiarContrasena").modal('hide');
                    },
                    error: function (error) {
                        console.log("error");
                        console.log(error.responseText);
                    }
                });
            })
        })




        $(document).on('click', '.cambiar-contrasena', function(event){
            event.preventDefault();

            vUserId = $(this).attr("user-id");
            vUsuario = $(this).attr("usuario");

            $('#sUsuario').text(vUsuario);

            $("#modalCambiarContrasena").modal('show');
        })

        $(document).on('click', '.delete', function(event){
            event.preventDefault();

            vUserId = $(this).attr("user-id");
            var vAccion = $(this).text().trim();

            var vTextSwal = vAccion === 'Desactivar' ? 'El registro quedara desactivado' : 'El registro se reactivara';

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
                        url: "{{route('users.delete')}}",
                        dataType: "json",
                        type: "POST",
                        data: {
                            id: vUserId,
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