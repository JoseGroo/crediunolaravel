@php
    $user = Auth::user();
    $rol = \App\Helpers\HelperCrediuno::get_rol_by_id($user->rol_id);
@endphp

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">


            <li class="nav-header shadow">
                <a class="navbar-minimalize" href="#">
                    <span></span>
                </a>
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/crediuno.png') }}" title="@DiccionarioSistema.NombreSistema" class="white" height="40">
                </a>
            </li>

            <li class="{{ HelperCrediuno::ActiveMenuControllers(array('')) }}">
                <a href="{{ route('home') }}" aria-expanded="false">
                    <i class="mdi mdi-home"></i>
                    <span class="nav-label">Inicio</span>
                </a>
            </li>


            @switch($rol->rol)
                @case("Administrador general")
                    @include('layouts.menu_rol._administrador_general')
                @break

                @case("Administrador")

                @break

                @case("Ventanilla")

                @break

                @case("Cobranza")

                @break

            @endswitch

        </ul>

    </div>
</nav>
