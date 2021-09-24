<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class estatus_adeudos extends Enum implements LocalizedEnum
{
    const Vigente = 1;
    const Liquidado =   2;
}