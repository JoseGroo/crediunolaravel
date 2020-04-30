<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class catalago_sistema extends Enum implements LocalizedEnum
{
    const Usuarios =   1;
    const Sucursales =   2;
    const Grupos = 3;
    const Intereses = 4;
    const Fondos = 5;
    const DiasFestivos = 6;
}
