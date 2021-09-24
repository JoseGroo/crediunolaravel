<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipos_garantia extends Enum implements LocalizedEnum
{
    const DeVista = 1;
    const ConDocumentos = 2;
    const Vehicular = 3;
    const Empeno = 4;
}