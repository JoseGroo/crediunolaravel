<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class periodos_prestamos extends Enum implements LocalizedEnum
{
    const Diario =   1;
    const Semanal =   2;
    const Quincenal = 3;
    const Mensual = 4;
}
