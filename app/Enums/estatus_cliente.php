<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class estatus_cliente extends Enum implements LocalizedEnum
{
    const Acreditado = 1;
    const Desacreditado = 2;
    const Trunco = 3;
    const EnInvestigacion = 4;
    const Juridico = 5;
    const Incobrable = 6;
    const Rechazado = 7;
}