<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class tipo_nota extends Enum implements LocalizedEnum
{
    const Administrador = 1;
    const Ventanilla = 2;
}