<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipo_transferencia_fondo extends Enum implements LocalizedEnum
{
    const Transferencia = 1;
    const Retiro = 2;
}
