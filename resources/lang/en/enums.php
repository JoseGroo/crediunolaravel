<?php

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Enums\tipo_fondo;

return [

    movimiento_bitacora::class => [
        movimiento_bitacora::InicioSesion => 'Inicio sesión',
        movimiento_bitacora::CerroSesion => 'Cerro sesión',
        movimiento_bitacora::Edicion => 'Edición',
        movimiento_bitacora::CreoNuevoRegistro => 'Creo nuevo registro',
        movimiento_bitacora::Desactivado => 'Desactivo',
        movimiento_bitacora::Reactivado => 'Reactivo',
        movimiento_bitacora::Elimino => 'Elimino',
        movimiento_bitacora::CambioContrasena => 'Cambio contraseña'
    ],


    catalago_sistema::class => [
        catalago_sistema::Usuarios => 'Usuarios',
        catalago_sistema::Sucursales => 'Sucursales',
        catalago_sistema::Grupos => 'Grupos',
        catalago_sistema::Intereses => 'Intereses',
        catalago_sistema::Fondos => 'Fondos',
        catalago_sistema::DiasFestivos => 'Días festivos',
    ],


    tipo_fondo::class => [
        tipo_fondo::Bancario => 'Bancario',
        tipo_fondo::Consigna => 'De consigna',
    ],

];