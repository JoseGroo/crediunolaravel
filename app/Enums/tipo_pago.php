<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipo_pago extends Enum implements LocalizedEnum
{
    const Adeudo = 1;
    const Cargo = 2;
}