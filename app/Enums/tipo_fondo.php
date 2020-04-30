<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipo_fondo extends Enum implements LocalizedEnum
{
    const Bancario =   1;
    const Consigna =   2;
}