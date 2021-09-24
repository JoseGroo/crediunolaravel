<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipos_movimientos_corte extends Enum implements LocalizedEnum
{
    const EntregaPrestamo = 1;
    const PagosRecibos = 2;
    const PagosCargos = 3;
}