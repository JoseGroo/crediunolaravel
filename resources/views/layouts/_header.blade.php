
@php
    $user = Auth::user();
    $nombre_completo = $user->nombre.' '.$user->apellido_paterno.' '.$user->apellido_materno;

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
            <form role="search" class="navbar-form-custom" style="width: 400px;" action="{{ route('clientes.search') }}">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" name="nombre" data-placeholder="Buscar cliente..." placeholder="Buscar cliente...">
                        <span class="input-group-append">
                            <button type="submit" class="btn-xs btn-primary "><i class="mdi mdi-account-search" style="font-size: 40px;"></i></button>
                        </span>
                    </div>
                </div>
            </form>
        </div>


        <ul class="nav navbar-top-links navbar-right">


            <li class="nav-item icon dropdown">
                <a class="nav-linkdropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false" id="dropdownMenuButton">
                    <i class="mdi mdi-plus-circle-outline"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="{{ route('clientes.create') }}">
                        <span class="mdi mdi-file-plus-outline"></span>
                        Nuevo cliente
                    </a>
                    <a class="dropdown-item" href="{{ route('divisas.compra_venta') }}">
                        <span class="mdi mdi-cash"></span>
                        Compar/Venta de divisa
                    </a>

                   @if(!$user->tiene_corte_abierto)
                        <a class="dropdown-item" onclick="event.preventDefault(); $('#frmAbrirCorte').submit();">
                            <span class="mdi mdi-file-plus-outline"></span>
                            Abrir corte
                            {{ Form::open([ 'route' => ['cortes.create_post' ], 'method' => 'POST', 'id' => 'frmAbrirCorte', 'style' => 'display:none;' ]) }}
                            {{ Form::close() }}
                        </a>
                   @endif
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
                            {{ \Illuminate\Support\Str::of($nombre_completo)->limit(14) }}
                        </span>
                        <small>{{ $rol->rol }}</small>
                        <i class="mdi mdi-chevron-down"></i>
                </span>
                </a>
                <div class="dropdown-menu">
                    {{--<a class="dropdown-item font-weight-bold" href="{{ route('login') }}">
                        Mi Perfil
                    </a>--}}

                    <a class="dropdown-item font-weight-bold" href="#" onclick="event.preventDefault(); $('#logoutForm').submit();">
                        Cerrar sesión
                        {{ Form::open([ 'route' => ['system.logoff' ], 'method' => 'POST', 'id' => 'logoutForm', 'style' => 'display:none;' ]) }}
                        {{ Form::close() }}
                    </a>
                </div>
            </li>

        </ul>

    </nav>
</div>
