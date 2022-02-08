<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class archivos_descarga extends Enum implements LocalizedEnum
{
    const SolicitudCliente = 1;
    const AvisoCobro = 2;
    const Cuestionario = 3;
    const HojaLogo = 4;
    const FormatoIngresos = 5;
    const FormatoCorte = 6;
}
