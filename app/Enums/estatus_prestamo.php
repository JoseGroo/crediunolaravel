<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class estatus_prestamo extends Enum implements LocalizedEnum
{
    const Generado = 1;
    const Vigente =   2;
    const Liquidado =   3;
    const Inactivo =   4;
    const Cancelado =   5;
}
