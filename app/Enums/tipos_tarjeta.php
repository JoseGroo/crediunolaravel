<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipos_tarjeta extends Enum implements LocalizedEnum
{
    const Debito = 1;
    const Credito =   2;
}
