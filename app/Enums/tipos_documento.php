<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipos_documento extends Enum implements LocalizedEnum
{
    const Identificacion = 1;
    const ComprobanteDomicilio = 2;
    const ComprobanteIngresos = 3;
    const Otro = 4;
}