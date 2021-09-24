<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class sexo extends Enum implements LocalizedEnum
{
    const Masculino = 1;
    const Femenino = 2;
}