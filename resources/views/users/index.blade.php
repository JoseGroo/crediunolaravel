@extends('layouts.layout')

@section("title", "Listado de usuarios")

@section('create_button')
    <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('users.create') }}">Crear nuevo usuario</a>
@endsection

@section('content')
    {{--<div class="row m-b-md">
        <div class="col-md-12">
            <a class="btn btn-sm btn-info" style="float:right;" href="{{ route('users.create') }}">Crear nuevo usuario</a>
        </div>
    </div>--}}
    {{ Form::open([ 'route' => ['users.index' ], 'method' => 'GET', 'id' => 'frmIndex' ]) }}

        <div class="card">

            <div class="card-header">
                <h5 class="card-title">Filtros</h5>
                <div class="form-row">

                    <div class="col-md-4 col-sm-3 col-12">
                        <div class="form-group">
                            <label>Nombre completo</label>
                            <input type="text" autocomplete="off" name="sNombre" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-12 text-left">
                        <label class="d-block">&nbsp;</label>
                        <button class="btn btn-sm btn-blue filtrar" type="submit">Buscar</button>
                        <a href="{{ route('users.index') }}" id="btnCleanFilter" class="btn btn-sm btn-white">Limpiar filtros</a>
                    </div>
                </div>
            </div>


            <div class="card-body">

                @include("users._index")

            </div>
        </div>
    {{ Form::close() }}

    <form id="frmCambiarContrasena">
        @csrf
        <input type="hidden" value="" id="id" name="id" >

        <div class="modal fade" tabindex="-1" role="dialog" id="modalCambiarContrasena">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cambiar contraseña usuario: <span id="sUsuario"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    {{ Form::label('password', 'Contraseña') }}
                                    {{ Form::password('password', [ 'class' => 'form-control' ]) }}
                                    <span id="password_validate"></span>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="form-group">
                                    {{ Form::label('confirmar_password', 'Confirmar contraseña') }}
                                    {{ Form::password('confirmar_password', [ 'class' => 'form-control' ]) }}
                                    <span id="confirmar_password_validate"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


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