@php
    $controllers_catalagos = [
        'fondos',
        'grupos-cliente',
        'intereses',
        'sucursales',
        'users',
        'dias-festivos'
        ];
@endphp

<li class="{{ HelperCrediuno::ActiveMenuControllers($controllers_catalagos) }}">
    <a href="#">
        <i class="mdi mdi-library-books"></i>
        <span class="nav-label">Catalagos </span>
        <span class="mdi mdi-chevron-down"></span>
    </a>
    <ul class="nav nav-second-level collapse ">
        <li>
            <a href="{{ route('dias_festivos.index') }}">Días festivos</a>
            <a href="{{ route('fondos.index') }}">Fondos</a>
            <a href="{{ route('grupos-cliente.index') }}">Grupos</a>
            <a href="{{ route('intereses.index') }}">Intereses</a>
            <a href="{{ route('sucursales.index') }}">Sucursales</a>
            <a href="{{ route('users.index') }}">Usuarios</a>
        </li>
    </ul>
</li>

<li class="{{ HelperCrediuno::ActiveMenuControllers(array('bitacora')) }}">
    <a href="{{ route('bitacora.index') }}" aria-expanded="false">
        <i class="mdi mdi-clipboard-list"></i>
        <span class="nav-label">Bitacora</span>
    </a>
</li>