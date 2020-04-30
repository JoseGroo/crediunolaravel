
@php
    $user = Auth::user();
    $nombre_completo = $user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno;
    $nombre_wrapper = strlen($nombre_completo) > 14 ? substr($nombre_completo, 0, 14).'...' : $nombre_completo;

    $rol = \App\Helpers\HelperCrediuno::get_rol_by_id($user->rol_id);

    $foto_perfil =  Storage::exists($user->foto) ? $user->foto : "public/user_profile/default.png";
@endphp
<div class="row">
    <nav class="navbar navbar-static-top white-bg shadow" role="navigation">

        <div class="d-flex">
            <div class="navbar-header">
                <a class="navbar-brand" href="#url aqui">
                <img src="{{ asset('images/crediuno.png') }}" class="dark" height="40">
                </a>
            </div>
            <ul class="nav navbar-top-links navbar-left">
                <li class="nav-item company">
                    <a class="nav-link text-left" href="#">
                        <span class="name">
                            Crediuno
                        </span>
                    </a>
                </li>
            </ul>
        </div>


        <ul class="nav navbar-top-links navbar-right">


            <li class="nav-item icon dropdown">
                <a class="nav-linkdropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false" id="dropdownMenuButton">
                    <i class="mdi mdi-plus-circle-outline"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#url">
                                <span class="mdi mdi-file-plus-outline"></span>
                                Nueva solicitud
                            </a>

                            <a class="dropdown-item" href="#url">
                                <span class="mdi mdi-account-plus"></span>
                                Nuevo usuario
                            </a>
                        </div>
            </li>

            <li class="nav-item separator">
                <span></span>
            </li>


            <li class="nav-item dropdown profile">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false">
                <span class="avatar">
                    <img width="40" height="40" alt="image" src="{{ Storage::url($foto_perfil) }}">
                </span>
                    <span class="name">

                        <span title="{{ $nombre_completo }}">
                            {{ $nombre_wrapper }}
                        </span>
                        <small>{{ $rol->rol }}</small>
                        <i class="mdi mdi-chevron-down"></i>
                </span>
                </a>
                <div class="dropdown-menu">
                    {{--<a class="dropdown-item font-weight-bold" href="{{ route('login') }}">
                        Mi Perfil
                    </a>--}}

                    <a class="dropdown-item font-weight-bold" href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                        Cerrar sesión
                        {{ Form::open([ 'route' => ['system.logoff' ], 'method' => 'POST', 'id' => 'logoutForm', 'style' => 'display:none;' ]) }}
                        {{ Form::close() }}
                    </a>
                </div>
            </li>

        </ul>

    </nav>
</div>
