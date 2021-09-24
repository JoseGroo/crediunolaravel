<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class estado_civil extends Enum implements LocalizedEnum
{
    const Soltero = 1;
    const Casado = 2;
    const Viudo = 3;
    const UnionLibre = 4;
    const Divorciado = 5;
    const Separado = 6;
}