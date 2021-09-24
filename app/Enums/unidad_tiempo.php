<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class unidad_tiempo extends Enum implements LocalizedEnum
{
    const Mes = 1;
    const Ano = 2;
}