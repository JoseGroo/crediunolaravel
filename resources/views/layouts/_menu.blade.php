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

            <li>
                <a href="{{ route('prestamos.index') }}" aria-expanded="false">
                    <i class="mdi mdi-cash-refund"></i>
                    <span class="nav-label">Cobranza</span>
                </a>
            </li>

            <li class="{{ HelperCrediuno::ActiveMenuControllers(array('avales')) }}">
                <a href="{{ route('avales.index') }}" aria-expanded="false">
                    <i class="mdi mdi-account-cash"></i>
                    <span class="nav-label">Avales</span>
                </a>
            </li>

            <li class="{{ HelperCrediuno::ActiveMenuControllers(array('clientes')) }}">
                <a href="{{ route('clientes.index') }}" aria-expanded="false">
                    <i class="mdi mdi-account-group-outline"></i>
                    <span class="nav-label">Clientes</span>
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
