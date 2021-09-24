<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipo_adeudo extends Enum implements LocalizedEnum
{
    const Recibo = 1;
    const Capital = 2;
}