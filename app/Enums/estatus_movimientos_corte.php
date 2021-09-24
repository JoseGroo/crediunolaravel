<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class estatus_movimientos_corte extends Enum implements LocalizedEnum
{
    const Activo = 1;
    const Cancelado =   2;
}
