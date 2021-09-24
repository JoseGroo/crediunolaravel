<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipo_intereses extends Enum implements LocalizedEnum
{
    const Flat =   1;
    const Empenio =   2;
    const Micronegocios = 4;
}
