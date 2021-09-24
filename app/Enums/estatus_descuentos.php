<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class estatus_descuentos extends Enum implements LocalizedEnum
{
    const Acreditado = 3;
    const Cancelado = 4;
    const Vencido = 2;
    const Vigente = 1;
}