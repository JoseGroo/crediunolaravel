<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipo_compra_venta_divisa extends Enum implements LocalizedEnum
{
    const Compra = 1;
    const Venta = 2;
}
