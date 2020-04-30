<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class movimiento_bitacora extends Enum implements LocalizedEnum
{
    const InicioSesion =   1;
    const CerroSesion =   2;
    const Edicion = 3;
    const CreoNuevoRegistro = 4;
    const Desactivado = 5;
    const Reactivado = 6;
    const Elimino = 7;
    const CambioContrasena = 8;
}