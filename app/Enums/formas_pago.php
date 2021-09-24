<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class formas_pago extends Enum implements LocalizedEnum
{
    const Efectivo = 1;
    const Cheque = 2;
    const Tarjeta = 3;
    const Descuento = 4;
    const FichaDeposito = 5;
    const TransferenciaElectronica = 6;
    const Refinanciar = 7;
    const Retencion = 8;
}


