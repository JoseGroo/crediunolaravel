@extends('layouts.layout_login')

@section("title", "Iniciar sesión")


@section('content')



    <div class="login-panel">
        <div class="animated fadeInDown ">
            <div class="bg-white border-radius-xl show border">
                <div class="row no-gutters h-100 full-height justify-content-center">
                    <div class="col-12 col-sm-12 col-md-6">
                        <div class="p-5">

                            <p class="text-center text-uppercase text-muted font-weight-light mb-0 letter-space-3"><small>Bienvenido a</small></p>

                            <img src="{{ asset('images/crediuno.png') }}" title="Crediuno" height="100" class="m-auto text-center d-block my-3">


                            <div class="w-75 m-auto text-muted text-center">
                                <small>Bienvenido, puedes iniciar sesión con tu correo electrónico y contraseña</small>
                            </div>

                            <form action="{{ route('login_post') }}" method="POST" id="frmLogin">

                                {{ csrf_field() }}

                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible" role="alert">

                                        <div class="row">
                                            <div class="col-12">
                                                {{ session('error') }}
                                            </div>
                                        </div>

                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <div class="form-group">
                                    {{ Form::label('usuario', 'Usuario') }}
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <span class="mdi mdi-account-outline"></span>
                                        </span>
                                        </div>

                                        {{ Form::text('usuario', old('usuario'), [ 'class' => 'form-control', 'placeholder' => 'Usuario', 'autofocus' => 'true' ]) }}

                                    </div>
                                    <div id="usuario_validate"></div>
                                </div>

                                <div class="form-group">
                                    {{ Form::label('contrasena', 'Contraseña') }}
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <span class="mdi mdi-account-outline"></span>
                                        </span>
                                        </div>

                                        {{ Form::password('contrasena', [ 'class' => 'form-control', 'placeholder' => 'Contraseña']) }}
                                    </div>
                                    <div id="contrasena_validate"></div>
                                </div>

                                <div class="form-row">

                                    <div class="col-md-6 m-t-sm">
                                        {{--<a href="#" class="" id="iForgotPassword"><span>¿Olvidó su contraseña?</span></a>--}}
                                    </div>


                                    <div class="col-sm-6 text-right">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-dark">
                                                Iniciar sesión
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </form>


                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 d-none d-sm-none d-md-block">
                        <div class="bg-panel-login border-radius-right-xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal inmodal" id="mForgotPassword" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content animated bounceInUp">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">@DiccionarioSistema.RecuperarContrasena</h4>
                </div>

                <div class="modal-body">
                    <div id="ibox-recover">

                    </div>
                    <p>@DiccionarioSistema.MensajeRecuperarContrasena1</p>
                    <p>@DiccionarioSistema.MensajeRecuperarContrasena2</p>
                    <div class="form-group">
                        <input type="text" class="form-control" id="txbUsername" placeholder="@DiccionarioSistema.CorreoElectronico" />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">@DiccionarioSistema.Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnRecover">@DiccionarioSistema.Recuperar</button>
                </div>

            </div>
        </div>
    </div>
@endsection



@section("scripts")
    @parent


    <script>



        $(function()
        {
            $("#frmLogin").validate({
                rules: {
                    usuario: "required",
                    contrasena: "required"
                },
                errorPlacement: function (error, element) {
                    var name = $(element).attr("name");
                    error.appendTo($("#" + name + "_validate"));
                },
            });

            $("#frmLogin").submit(function()
            {
                var vSubmit = $(this).valid();
                if (vSubmit) {
                    $('button[type=submit], input[type=submit]').prop('disabled', true);
                    ShowLoading("Validando credenciales...");
                }
                return vSubmit;
            })

            $("#iRememberMe").click(function()
            {
                var vRememberMe = $(this).prop("checked");
                $("#bRememberMe").val(vRememberMe);
            })

            $("#iForgotPassword").click(function()
            {
                event.preventDefault();
                $("#mForgotPassword").modal("show");
                setTimeout(function()
                {
                    $("#txbUsername").focus();
                },500)
            })

            $("#txbUsername").keypress(function()
            {
                if(event.charCode == 13)
                {
                    $("#btnRecover").trigger("click");
                }
            })

            $("#btnRecover").click(function()
            {
                var vUser = $("#txbUsername").val();
                if (vUser.length == 0)
                {
                    MyToast("@DiccionarioSistema.Aviso", "@DiccionarioSistema.CorreoElectronicoObligatorioMensaje", "error", "8000");
                    return;
                }
                $("#ibox-email").children('.ibox-content').addClass('sk-loading');
                $.ajax({
                    url: '@Url.Action("RecoverPassword")',
                    type: "POST",
                    data: JSON.stringify({
                        'sUsername': vUser
                    }),
                    datatype: 'json',
                    cache: false,
                    contentType: 'application/json; charset=utf-8',
                    success: function (data) {
                        if(data.length == 0)
                        {
                            MyToast("@DiccionarioSistema.Aviso", "@DiccionarioSistema.MensajeEnvioContrasena", "success");
                            $("#txbUsername").val("");
                            $("#mForgotPassword").modal("hide");
                        }else
                        {
                            MyToast("Aviso!", data, "error");
                        }
                        HideLoading();
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(exception);
                    }

                });
            })
        })
    </script>

@endsection
