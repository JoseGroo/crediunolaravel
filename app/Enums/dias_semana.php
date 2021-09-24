<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class dias_semana extends Enum implements LocalizedEnum
{
    const Domingo = 0;
    const Lunes =   1;
    const Martes =   2;
    const Miercoles = 3;
    const Jueves = 4;
    const Viernes = 5;
    const Sabado = 6;
}
